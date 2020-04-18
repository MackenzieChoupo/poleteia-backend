<?php

namespace Politeia\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Politeia\CoreBundle\Entity\BoiteAIdeeQuestion;

class BoiteAIdeeQuestionAdmin extends Admin
{
    protected $baseRoutePattern = 'bai-questions';
    
    protected $datagridValues = array(

        // display the first page (default = 1)
        '_page' => 1,

        // reverse order (default = 'ASC')
        '_sort_order' => 'DESC',

        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'updated',
    );
    
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
            ->add('titre', null, array('label' => 'Titre', 'advanced_filter' => false))
            /*->add('theme', null, array('label' => 'Thème'), null, array(
                    'class' => 'Politeia\CoreBundle\Entity\BoiteAIdeeTheme',                    
                    'query_builder' => $this->modelManager->getEntityManager('Politeia\CoreBundle\Entity\BoiteAIdeeTheme')
                            ->createQueryBuilder('o')
                            ->from('PoliteiaCoreBundle:BoiteAIdeeTheme', 'o')
                            ->select('o')
                            ->where('o.mairie = :mairie')
                            ->orderBy('o.titre', 'ASC')
                            ->setParameter('mairie', $this->getUser()->getProfil()->getMairie())))*/
            ->add('theme', null, array('label' => 'Thème'), 'choice', array(
                'choices' => array(
                    BoiteAIdeeQuestion::THEME_EDUCATION_JEUNESSE => 'boite_a_idee.theme.'.BoiteAIdeeQuestion::THEME_EDUCATION_JEUNESSE,
                    BoiteAIdeeQuestion::THEME_SOCIAL => 'boite_a_idee.theme.'.BoiteAIdeeQuestion::THEME_SOCIAL,
                    BoiteAIdeeQuestion::THEME_URBANISME_TRAVAUX => 'boite_a_idee.theme.'.BoiteAIdeeQuestion::THEME_URBANISME_TRAVAUX,
                    BoiteAIdeeQuestion::THEME_ANIMATION => 'boite_a_idee.theme.'.BoiteAIdeeQuestion::THEME_ANIMATION                   
                )                
            ))   
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
            ->addIdentifier('titre')
            ->add('theme', 'choice', array(
                'label' => 'Thème',
                'choices' => array(
                    BoiteAIdeeQuestion::THEME_EDUCATION_JEUNESSE => 'Éduction & Jeunesse.',
                    BoiteAIdeeQuestion::THEME_SOCIAL => 'Social',
                    BoiteAIdeeQuestion::THEME_URBANISME_TRAVAUX => 'Urbanisme & Travaux',
                    BoiteAIdeeQuestion::THEME_ANIMATION => 'Animation'                  
                )
            ))  
            ->add('nbReponse', null, array(
                'label' => 'Nb réponses'
            )) 
            ->add('online', null, array(
                'label' => 'En ligne',
                'editable' => true
            ))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),   
                    'delete' => array(),
                )
            ))
        ;
    }
    
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->with('Question', array(                    
                'class' => 'col-md-6 col-sm-6 frm-texte',
                'box_class' => 'box box-solid box-danger'
            ))
                ->add('titre', null, array(
                    'label' => 'Titre',
                    'required' => true,
                    'constraints' => array(
                        new Assert\NotBlank()                           
                    )
                ))
                /*->add('theme', 'entity', array(
                    'label' => 'Thème',
                    'class' => 'Politeia\CoreBundle\Entity\BoiteAIdeeTheme',
                    'query_builder' => $this->modelManager->getEntityManager('Politeia\CoreBundle\Entity\BoiteAIdeeTheme')
                            ->createQueryBuilder('o')
                            ->from('PoliteiaCoreBundle:BoiteAIdeeTheme', 'o')
                            ->select('o')
                            ->where('o.mairie = :mairie')
                            ->orderBy('o.titre', 'ASC')
                            ->setParameter('mairie', $this->getUser()->getProfil()->getMairie()),
                    'required' => true,
                    'constraints' => array(
                        new Assert\NotBlank()
                    )
                ))*/
                ->add('theme', 'choice', array(
                    'label' => 'Thème',
                    'choices' => array(
                        BoiteAIdeeQuestion::THEME_EDUCATION_JEUNESSE => 'boite_a_idee.theme.'.BoiteAIdeeQuestion::THEME_EDUCATION_JEUNESSE,
                        BoiteAIdeeQuestion::THEME_SOCIAL => 'boite_a_idee.theme.'.BoiteAIdeeQuestion::THEME_SOCIAL,
                        BoiteAIdeeQuestion::THEME_URBANISME_TRAVAUX => 'boite_a_idee.theme.'.BoiteAIdeeQuestion::THEME_URBANISME_TRAVAUX,
                        BoiteAIdeeQuestion::THEME_ANIMATION => 'boite_a_idee.theme.'.BoiteAIdeeQuestion::THEME_ANIMATION                   
                    ),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                    'constraints' => array(
                        new Assert\NotBlank()
                    )
                ))                
                ->add('datePublicationDebut', 'sonata_type_datetime_picker', array(
                    'label' => 'Date de publication début',
                    'datepicker_use_button' => false,
                    'format' => 'dd/MM/yyyy HH:mm',
                    'required' => true,
                    'constraints' => array(
                        new Assert\DateTime(),
                        //new Assert\GreaterThan(array('value' => (new \DateTime())->setTime(0, 0, 0)))
                    )
                ))                    
                ->add('datePublicationFin', 'sonata_type_datetime_picker', array(
                    'label' => 'Date de publication fin',
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
        ;
    }
    
    public function isGranted($name, $object = null)
    {      
        if($name === 'DELETE' && $object instanceof \Politeia\CoreBundle\Entity\BoiteAIdeeQuestion) {
            return $object->isDeletable() && parent::isGranted($name, $object);
        } else {        
            return parent::isGranted($name, $object);
        }
    } 
    
    
    public function prePersist($object)
    {        
        $object->setMairie($this->getUser()->getProfil()->getMairie());
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
