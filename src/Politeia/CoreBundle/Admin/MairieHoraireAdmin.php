<?php
namespace Politeia\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Description of MairieHoraireAdmin
 *
 * @author remi
 */
class MairieHoraireAdmin extends Admin {

    protected $baseRoutePattern = 'mairies/horaires';
    
    protected function configureListFields(ListMapper $list) {
        
    }

    protected function configureDatagridFilters(DatagridMapper $filter) {
        
    }

    protected function configureFormFields(FormMapper $form) {
        $form
                ->add('jour', null, array(
                    'label' => 'Jour'
                ))
                ->add('detail', null, array(
                    'label' => 'Détail'
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
