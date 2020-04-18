<?php

namespace Politeia\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class BoiteAIdeeThemeAdmin extends Admin
{
    protected $baseRoutePattern = 'bai-themes';
    
    protected $datagridValues = array(

        // display the first page (default = 1)
        '_page' => 1,

        // reverse order (default = 'ASC')
        '_sort_order' => 'ASC',

        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'position',
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
    
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('titre')
            ->add('texte', 'text', array(
                'label' => 'Texte',
                'template' => 'PoliteiaCoreBundle:CRUD:list_field_texte_texte.html.twig'
            ))
            ->add('nbQuestion', null, array(
                'label' => 'Nb questions'
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
            //->tab('Theme')
                ->with('Theme', array(                    
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
                    ->add('texte', 'textarea', array(
                        'label' => 'Texte',
                        'required' => false,
                        'attr' => array(
                            'class' => 'texte-simple-wysiwyg',
                            'rows' => 5
                        ),
                        'constraints' => array(                                              
                        )
                    ))
                    ->add('online', null, array(
                        'label' => 'En ligne',
                        'required' => false
                    ))
                ->end()
            //->end()
//            ->tab('Questions')
//                ->with('Liste des questions', array(                    
//                    'class' => 'col-md-12 col-sm-12',
//                    'box_class' => 'box box-solid box-danger'
//                ))
//                    ->add('questions', 'sonata_type_collection', array(
//                        'label' => false,
//                        'help' => '',
//    //                    'by_reference' => false,
//                        'type_options' => array(
//                            'delete' => true,
//                        ),
//                            ), array(
//                        'edit' => 'inline',
//                        'inline' => 'table',
//                        'sortable' => 'position',
//                        'allow_delete' => true,
//                        'allow_add' => true,
//                    ))
//                ->end()
//            ->end()
        ;
    }
    
    public function isGranted($name, $object = null)
    {      
        if($name === 'DELETE' && $object instanceof \Politeia\CoreBundle\Entity\BoiteAIdeeTheme) {
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
