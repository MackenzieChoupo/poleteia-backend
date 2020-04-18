<?php

namespace Politeia\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class PlanningAdmin extends Admin
{
    protected $baseRoutePattern = 'planning';
    
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
    
    protected function configureListFields(ListMapper $list)
    {
        $list            
            ->addIdentifier('titre', 'text', array(
                'label' => 'Titre'                
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
            //->tab('Alerte')
                ->with('Informations', array(                    
                    'class' => 'col-md-6 col-sm-6 frm-texte',
                    'box_class' => 'box box-solid box-danger'
                ))                    
                    ->add('titre', 'text', array(
                        'label' => 'Titre',                        
                        'required' => true,
                        'constraints' => array(
                            new Assert\NotBlank()                           
                        )
                    ))
                    ->add('pdfFile', 'file', array(
                        'label' => 'Fichier PDF',                            
                        'required' => ($subject->getPdfName() === null),
                        'help' => ''
                    ))
                    ->add('pdf', new \Politeia\CoreBundle\Form\Type\PdfFileType(), array(
                        'label' => false,
                        'required' => false,
                        'mapped' => false,
                        'data' => (int)$subject->getId() > 0 ? $subject->getPdfName() : ''
                    ), array(
                        'type' => 'text'
                    ))
                                     
                ->end()
                ->with('Publication', array(                    
                    'class' => 'col-md-6 col-sm-6 frm-texte',
                    'box_class' => 'box box-solid box-danger'
                ))                    
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

    /**
     * 
     * @return \Kreatys\UserBundle\Entity\User
     */
    private function getUser()
    {
        return $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
    }
}
