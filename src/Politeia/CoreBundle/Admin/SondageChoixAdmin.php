<?php

namespace Politeia\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SondageChoixAdmin extends Admin
{
    protected $baseRoutePattern = 'sondages/choix';
    
 
    protected function configureListFields(ListMapper $list)
    {
        
    }
    
    protected function configureFormFields(FormMapper $form)
    {
        $form            
            ->add('texte', null, array(
                'label' => 'Texte',
                'required' => true,
                'constraints' => array(
                    new Assert\NotBlank()                           
                )
            ))
            ->add('position', 'hidden', array(
                'label' => false,
                'attr' => array(
                    'hidden' => true
                )
            ))
        ;
    }

}
