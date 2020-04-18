<?php

namespace Politeia\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class PubliciteAdmin extends Admin
{
    protected $baseRoutePattern = 'publicite';
    
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            //->add('lien', null, array('label' => 'lien', 'advanced_filter' => false))           
            ->add('datePublicationDebut', 'doctrine_orm_date_range', array('label' => 'Date publication début', 'field_type' => 'sonata_type_date_range_picker'))
            ->add('datePublicationFin', 'doctrine_orm_date_range', array('label' => 'Date publication fin', 'field_type' => 'sonata_type_date_range_picker'))
        ;
    }
    
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('lien', null, array(
                'label' => 'Lien'
            ))
            ->add('mairies', null, array(
                'label' => 'Mairies'
            ))
            ->add('NbViews', null, array(
                'label' => 'Nb vues'
            ))
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
                    'class' => 'col-md-4 col-sm-4',
                    'box_class' => 'box box-solid box-danger'
                ))                    
                    ->add('lien', 'text', array(
                        'label' => 'Lien',                        
                        'required' => false,
                        'constraints' => array(
                            new Assert\Url()
                        ),
                        'help' => 'Format : http://wwww.example.com'
                    ))
                    ->add('imageFile', 'file', array(
                        'label' => 'Image',                            
                        'required' => (int)$subject->getId() === 0,
                        'help' => ''
                    ))
                    ->add('image', new \Politeia\CoreBundle\Form\Type\ImageFileType('publicite'), array(
                            'label' => false,
                            'required' => false,
                            'mapped' => false,
                            'data' => (int)$subject->getId() > 0 ? $subject->getImageName() : ''
                        ), array(
                            'type' => 'text'
                    )) 
                ->end()
                ->with('Mairies', array(                    
                    'class' => 'col-md-4 col-sm-4',
                    'box_class' => 'box box-solid box-danger'
                )) 
                    ->add('mairies', null, array(
                        'label' => 'Liste des mairies',
                        'required' => true,
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
}
