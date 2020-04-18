<?php

namespace Kreatys\CmsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;

/**
 * Description of SeoAdmin
 *
 * @author remi
 */
class SeoAdmin extends Admin {

    protected $baseRoutePattern = 'parametres/seo';

    protected function configureFormFields(\Sonata\AdminBundle\Form\FormMapper $form) {
        $form
                ->add('meta_title', null, array(
                    'label' => 'kreatys_cms.admin.parametres.seo.meta_title',
                    'required' => false
                ))
                ->add('meta_keywords', null, array(
                    'label' => 'kreatys_cms.admin.parametres.seo.meta_keywords',
                    'required' => false
                ))
                ->add('meta_description', null, array(
                    'label' => 'kreatys_cms.admin.parametres.seo.meta_description',
                    'attr' => array(
                        'rows' => '4'
                    ),
                    'required' => false
                ))
                ->add('footer', null, array(
                    'label' => 'kreatys_cms.admin.parametres.seo.footer',
                    'attr' => array(
                        'rows' => '6'
                    ),
                    'required' => false
                ))
                ->add('google_analitics', 'textarea', array(
                    'label' => 'kreatys_cms.admin.parametres.seo.googleAnalitics',
                    'attr' => array(
                        'rows' => '6'
                    ),
                    'required' => false
                ))
        ;
    }

//    protected function configureShowFields(\Sonata\AdminBundle\Show\ShowMapper $filter) {
//        $filter
//                ->with('kreatys_cms.admin.parametres.show.titre.general', array(
//                    'class' => 'col-md-6'
//                ))
//                ->add('logo')
//                ->end()
//                ->with('kreatys_cms.admin.parametres.show.titre.seo', array(
//                    'class' => 'col-md-6'
//                ))
//                ->add('seo.meta_title', null, array(
//                    'label' => 'kreatys_cms.admin.parametres.seo.meta_title'
//                ))
//                ->add('seo.meta_keywords', null, array(
//                    'label' => 'kreatys_cms.admin.parametres.seo.meta_keywords'
//                ))
//                ->add('seo.meta_description', null, array(
//                    'label' => 'kreatys_cms.admin.parametres.seo.meta_description'
//                ))
//                ->add('seo.footer', null, array(
//                    'label' => 'kreatys_cms.admin.parametres.seo.footer'
//                ))
//                ->end()
//        ;
//    }

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection) {
        $collection->clearExcept(array('edit', 'show'));
    }

}
