<?php

namespace Politeia\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Politeia\CoreBundle\Repository\SondageRepository;

class SondageAdmin extends Admin
{
    protected $baseRoutePattern = 'sondages';
    
    protected $datagridValues = array(

        // display the first page (default = 1)
        '_page' => 1,

        // reverse order (default = 'ASC')
        '_sort_order' => 'DESC',

        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'created',
    );
    
    /**
     * @var SondageRepository 
     */
    protected $sondageRepository;
    
    public function __construct($code, $class, $baseControllerName, $sondageRepository)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->sondageRepository = $sondageRepository;
    }

    
    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        
        $user = $this->getUser();
        if($user->hasRole('ROLE_MAIRIE')) {
            $query->join('o.mairie', 'm');          
            $query->where('m = :mairie');
            $query->setParameter('mairie', $user->getProfil()->getMairie());
        }
        
        return $query;
    }
    
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('question', null, array('label' => 'Question', 'advanced_filter' => false))            
            ->add('datePublicationDebut', 'doctrine_orm_date_range', array('label' => 'Date publication début', 'field_type' => 'sonata_type_date_range_picker'))
            ->add('datePublicationFin', 'doctrine_orm_date_range', array('label' => 'Date publication fin', 'field_type' => 'sonata_type_date_range_picker'))
            ->add('online', 'doctrine_orm_callback', array(
                'label' => false,
                'callback' => function($queryBuilder, $alias, $field, $value) {
                    if($value['value'] == '1') {                        
                        $queryBuilder->andWhere(sprintf('%s.online', $alias).' = :online'); 
                        $queryBuilder->andWhere(sprintf('%s.datePublicationDebut', $alias).' <= :now and '.sprintf('%s.datePublicationFin', $alias).' >= :now'); 
                        $queryBuilder->setParameter('online', true); 
                        $queryBuilder->setParameter('now', new \DateTime());                    
                    }
                    return true;
                }), 'checkbox', array('value' => '1', 'label' => 'Actuellement en ligne'))
        ;
    }
    
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('question')              
            ->add('nbReponse', null, array(
                'label' => 'Nb réponses'
            )) 
            ->add('datePublicationDebut', null, array(
                'label' => 'Date début'
            ))
            ->add('datePublicationFin', null, array(
                'label' => 'Date fin'
            ))
            ->add('online', null, array(
                'label' => 'En ligne',
                'editable' => true
            ))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),   
                    'delete' => array(),
                    'resultat' => array(
                        'template' => 'PoliteiaCoreBundle:CRUD:list__action_resultat.html.twig'
                    ),
                )
            ))
        ;
    }
    
    protected function configureFormFields(FormMapper $form)
    {
        $subject = $this->getSubject();

        $form
            ->with('Question', array(                    
                'class' => 'col-md-6 col-sm-6 frm-sondage',
                'box_class' => 'box box-solid box-danger'
            ))
                ->add('question', null, array(
                    'label' => 'Question',
                    'required' => true,
                    'constraints' => array(
                        new Assert\NotBlank()                           
                    )
                ))
                ->add('poseQuestionCible', 'choice', array(
                    'label' => 'Y a t-il une question pour clibler une catégorie de personnes ?',
                    'required' => true,  
                    'mapped' => false,
                    'choices' => array(
                        '0' => 'Non',
                        '1' => 'Oui'
                    ),
                    'expanded' => true,
                    'multiple' => false,
                    'data' => $subject instanceof \Politeia\CoreBundle\Entity\Sondage && (int)$subject->getId() > 0 && $subject->getQuestionCible() !== null ? 1 : 0
                )) 
                ->add('questionCible', null, array(
                    'label' => 'Question pour clibler des personnes',
                    'required' => false,
                    'help' => 'La question doit amener les utilisateurs à répondre par Oui ou Non uniquement.',
                    'constraints' => array(
                        new Assert\Callback(['callback' => function($value, ExecutionContextInterface $context) {
                            if($value === '') {
                                return;
                            }           
                            
                            if($context->getRoot()->get('poseQuestionCible')->getData() == '1') {      
                                $context->validateValue($value, new Assert\NotBlank());
                            }               
                        }])
                    )
                ))             
                ->add('datePublicationDebut', 'sonata_type_datetime_picker', array(
                    'label' => 'Date de début',
                    'datepicker_use_button' => false,
                    'format' => 'dd/MM/yyyy HH:mm',
                    'required' => true,
                    'constraints' => array(
                        new Assert\DateTime(),
                        //new Assert\GreaterThan(array('value' => (new \DateTime())->setTime(0, 0, 0)))
                        new Assert\Callback(['callback' => function($value, ExecutionContextInterface $context) use ($subject) {
                            $dateFin = $context->getRoot()->get('datePublicationFin')->getData();
                            
                            if($value instanceof \DateTime && $dateFin instanceof \DateTime) {                            
                                if($this->sondageRepository->checkSondageExist($this->getUser()->getProfil()->getMairie(), $value, $dateFin, (int)$subject->getId() > 0 ? $subject : null)) {
                                    $context->addViolation('Un sondage existe déjà dans cette plade de date');                                
                                }
                            }                                          
                        }])
                    )
                ))                    
                ->add('datePublicationFin', 'sonata_type_datetime_picker', array(
                    'label' => 'Date de fin',
                    'datepicker_use_button' => false,
                    'format' => 'dd/MM/yyyy HH:mm',
                    'required' => true,
                    'constraints' => array(
                        new Assert\DateTime(),
                        //new Assert\GreaterThan(array('value' => (new \DateTime())->setTime(0, 0, 0)))
                    )
                ))
                ->add('online', null, array(
                    'label' => 'En ligne',
                    'required' => false
                ))    
            ->end()
            ->with('Réponses', array(                    
                'class' => 'col-md-6 col-sm-6',
                'box_class' => 'box box-solid box-danger'
            ))
                ->add('choix', 'sonata_type_collection', array(
                        'label' => false,
                        'help' => '',
                        'type_options' => array(
                            'delete' => true,
                        ),
                        'constraints' => array(
                            new Assert\Callback(['callback' => function($value, ExecutionContextInterface $context) {
                                if($value === '') {
                                    return;
                                }           

                                if($value->count() < 2) {
                                    $context->addViolation('Vous devez saisir au moins 2 réponses possible');
                                }
                            }])
                        )
                    ), array(
                        'edit' => 'inline',
                        'inline' => 'table',
                        'sortable' => 'position',
                        'allow_delete' => true,
                        'allow_add' => true    
                    )
                )              
            ->end()
        ;
    }
    
    public function isGranted($name, $object = null)
    {      
        if($name === 'DELETE' && $object instanceof \Politeia\CoreBundle\Entity\Sondage) {
            return $object->isDeletable() && parent::isGranted($name, $object);
        } else {        
            return parent::isGranted($name, $object);
        }
    } 
    
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('resultat', $this->getRouterIdParameter().'/resultat');
    }
    
    public function prePersist($object)
    {        
        $object->setMairie($this->getUser()->getProfil()->getMairie());
        if($this->getForm()->get('poseQuestionCible')->getData() == '0') {
            $object->setQuestionCible(null);
        }
        foreach ($object->getChoix() as $choix) {
            $choix->setSondage($object);
        }
    } 
    
    public function preUpdate($object)
    {
        if($this->getForm()->get('poseQuestionCible')->getData() == '0') {
            $object->setQuestionCible(null);
        }
        foreach ($object->getChoix() as $choix) {
            $choix->setSondage($object);
        }
    }

    
    /**
     * 
     * @return \Kreatys\UserBundle\Entity\User
     */
    private function getUser()
    {
        return $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
    }
}
