<?php

namespace Politeia\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class NewsAdmin extends Admin
{
    protected $baseRoutePattern = 'news';
    
    protected $datagridValues = array(

        // display the first page (default = 1)
        '_page' => 1,

        // reverse order (default = 'ASC')
        '_sort_order' => 'DESC',

        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'datePublicationDebut',
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
            //->add('important', null, array('label' => 'News importante'), 'choice', array('choices' => array(2 => 'Non', 1 => 'Oui' )))            
            ->add('datePublicationDebut', 'doctrine_orm_date_range', array('label' => 'Date publication début', 'field_type' => 'sonata_type_date_range_picker'))
            ->add('datePublicationFin', 'doctrine_orm_date_range', array('label' => 'Date publication fin', 'field_type' => 'sonata_type_date_range_picker'))
            //->add('online', null, array('label' => 'En ligne'), 'choice', array('choices' => array(1 => 'Oui', 2 => 'Non')))    
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
            ->addIdentifier('titre', 'text', array(
                'label' => 'Titre'                
            ))
            ->add('texte', 'text', array(
                'label' => 'Texte',
                'template' => 'PoliteiaCoreBundle:CRUD:list_field_texte_texte.html.twig'
            ))
            /*->add('important', null, array(
                'label' => 'Important',
                'editable' => true
            ))*/            
            ->add('datePublicationDebut', 'date', array(
                'label' => 'Date publication début',
                'format' => 'd/m/Y'
            ))
            ->add('datePublicationFin', 'date', array(
                'label' => 'Date publication fin',
                'format' => 'd/m/Y'
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
        $subject = $this->getSubject();        
        
        $form
            //->tab('News')
                ->with('Informations', array(                    
                    'class' => 'col-md-8 col-sm-8 frm-texte',
                    'box_class' => 'box box-solid box-danger'
                ))                    
                    ->add('titre', 'text', array(
                        'label' => 'Titre',                        
                        'required' => true,
                        'constraints' => array(
                            new Assert\NotBlank()                           
                        )
                    ))                    
                    ->add('texte', 'textarea', array(
                        'label' => 'Texte',
                        'required' => false,
                        'attr' => array(
                            'class' => 'texte-simple-wysiwyg',
                            'rows' => 10
                        ),
                        'constraints' => array(
                            new Assert\NotBlank()                           
                        )
                    ))
                    ->add('photoFile', 'file', array(
                        'label' => 'Photo',                            
                        'required' => false,
                        'help' => ''
                    ))
                    ->add('photo', new \Politeia\CoreBundle\Form\Type\PhotoNewsFileType(), array(
                            'label' => false,
                            'required' => false,
                            'mapped' => false,
                            'data' => (int)$subject->getId() > 0 ? $subject->getPhotoName() : ''
                        ), array(
                            'type' => 'text'
                    )) 
                ->end()
                ->with('Publication', array(                    
                    'class' => 'col-md-4 col-sm-4 frm-texte',
                    'box_class' => 'box box-solid box-danger'
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
                    /*->add('important', null, array(
                        'label' => 'News importante',
                        'required' => false
                    ))*/
                    ->add('online', null, array(
                        'label' => 'En ligne',
                        'required' => false
                    ))                    
                ->end()
            //->end()
        ;
    }  
    
    public function prePersist($object)
    {        
        $object->setMairie($this->getUser()->getProfil()->getMairie());
    }


//    protected function configureRoutes(RouteCollection $collection)
//    {
//          
//    }
//    
//    public function getBatchActions()
//    {
//        return array();
//    }
    
//    public function isGranted($name, $object = null)
//    {      
//        if($name === 'DELETE' && $object instanceof \Politeia\CoreBundle\Entity\News) {
//            return $object->isDeletable() && parent::isGranted($name, $object);
//        } else {        
//            return parent::isGranted($name, $object);
//        }
//    } 
    
    /**
     * 
     * @return \Kreatys\UserBundle\Entity\User
     */
    private function getUser()
    {
        return $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
    }
}
