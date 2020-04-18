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

class BoiteAIdeeReponseAdmin extends Admin
{
    protected $baseRoutePattern = 'bai-reponses';
    
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
            $query->join('o.question', 'q');
            $query->join('q.mairie', 'm');          
            $query->where('m = :mairie');
            $query->setParameter('mairie', $user->getProfil()->getMairie());
        }
        
        return $query;
    }
    
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter      
            ->add('question.theme', null, array('label' => 'Thème'), 'choice', array(
                'choices' => array(
                    BoiteAIdeeQuestion::THEME_EDUCATION_JEUNESSE => 'boite_a_idee.theme.'.BoiteAIdeeQuestion::THEME_EDUCATION_JEUNESSE,
                    BoiteAIdeeQuestion::THEME_SOCIAL => 'boite_a_idee.theme.'.BoiteAIdeeQuestion::THEME_SOCIAL,
                    BoiteAIdeeQuestion::THEME_URBANISME_TRAVAUX => 'boite_a_idee.theme.'.BoiteAIdeeQuestion::THEME_URBANISME_TRAVAUX,
                    BoiteAIdeeQuestion::THEME_ANIMATION => 'boite_a_idee.theme.'.BoiteAIdeeQuestion::THEME_ANIMATION                   
                )                
            )) 
            ->add('question', null, array('label' => 'Question'), null, array(
                    'class' => 'Politeia\CoreBundle\Entity\BoiteAIdeeQuestion',                    
                    'query_builder' => $this->modelManager->getEntityManager('Politeia\CoreBundle\Entity\BoiteAIdeeQuestion')
                            ->createQueryBuilder('o')
                            ->from('PoliteiaCoreBundle:BoiteAIdeeQuestion', 'o')
                            ->select('o')                       
                            ->where('o.mairie = :mairie')
                            ->orderBy('o.titre', 'ASC')
                            ->setParameter('mairie', $this->getUser()->getProfil()->getMairie())))         
            
        ;
    }
    
    protected function configureListFields(ListMapper $list)
    {
        $list             
            ->add('question.theme', 'choice', array(
                'label' => 'Thème',
                'choices' => array(
                    BoiteAIdeeQuestion::THEME_EDUCATION_JEUNESSE => 'Éduction & Jeunesse.',
                    BoiteAIdeeQuestion::THEME_SOCIAL => 'Social',
                    BoiteAIdeeQuestion::THEME_URBANISME_TRAVAUX => 'Urbanisme & Travaux',
                    BoiteAIdeeQuestion::THEME_ANIMATION => 'Animation'                  
                )
            )) 
            ->add('question', null, array(
                'label' => 'Question'
            ))            
            ->add('texte', 'text', array(
                'label' => 'Texte',
                'template' => 'PoliteiaCoreBundle:CRUD:list_field_texte_texte.html.twig'
            ))
            ->add('citoyen.prenomNom', null, array(
                'label' => 'Utilisateur'
            ))
            ->add('updated', null, array(
                'label' => 'Date',
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
            ->with('Réponse', array(                    
                'class' => 'col-md-6 col-sm-6 frm-texte',
                'box_class' => 'box box-solid box-danger'
            ))                
                ->add('citoyen', new \Politeia\CoreBundle\Form\Type\TextInfosType(), array(
                    'label' => 'Utilisateur',
                    'data' => $this->getSubject()->getCitoyen()->getPrenomNom(),
                    'mapped' => false,
                ), array('type' => 'text'))
                ->add('texte', null, array(
                    'label' => 'Texte',
                    'required' => true,
                    'constraints' => array(
                        new Assert\NotBlank()                           
                    ),
                    'attr' => array(
                        'rows' => 10
                    )
                ))
            ->end()
        ;
    }
    
    protected function configureRoutes(RouteCollection $collection)
    {   
        $collection->clearExcept(array('list', 'edit', 'delete'));       
    }
    
    public function isGranted($name, $object = null)
    {      
        if($name === 'DELETE' && $object instanceof \Politeia\CoreBundle\Entity\BoiteAIdeeQuestion) {
            return $object->isDeletable() && parent::isGranted($name, $object);
        } else {        
            return parent::isGranted($name, $object);
        }
    }

    public function getBatchActions()
    {
        return array();
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
