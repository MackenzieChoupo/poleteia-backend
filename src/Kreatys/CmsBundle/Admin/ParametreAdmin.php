<?php

namespace Kreatys\CmsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Kreatys\CmsBundle\Form\TwoFieldsType;

/**
 * Description of ParametreAdmin
 *
 * @author remi
 */
class ParametreAdmin extends Admin {

    protected $baseRoutePattern = 'parametres';

    protected function configureFormFields(\Sonata\AdminBundle\Form\FormMapper $form) {
        $user = $this->getUser();
        
        $form
                ->tab('kreatys_cms.admin.parametres.form.tab.general')
                ->with('kreatys_cms.admin.parametres.form.titre.general', array(
                    'class' => 'col-md-6'
                ))
                ->add('logo', 'sonata_type_model_list', array(
                    'label' => 'kreatys_cms.admin.parametres.logo'
                ))
                ->add('logo_footer', 'sonata_type_model_list', array(
                    'label' => 'kreatys_cms.admin.parametres.logo_footer.label',
                    'help' => 'kreatys_cms.admin.parametres.logo_footer.help'
                ))
                ->end()
                ->end()
                ->tab('kreatys_cms.admin.parametres.form.tab.seo')
                ->with('kreatys_cms.admin.parametres.form.titre.seo', array(
                    'class' => 'col-md-6'
                ))
                ->add('seo', 'sonata_type_admin', array(
                    'label' => false,
                    'delete' => false
                ))
                ->end()
                ->end()
                ->tab('kreatys_cms.admin.parametres.form.tab.autre')
                ->with('kreatys_cms.admin.parametres.form.titre.autre', array(
                    'class' => 'col-md-4'
                ))
                ->add('autres', 'collection', array(
                    'label' => 'kreatys_cms.admin.parametres.autres',
                    'required' => false,
                    'type' => new TwoFieldsType($user->hasRole('ROLE_SUPER_ADMIN')),
                    'options' => array(
                        'required' => false,
                        'label' => false
                    ),
                    'allow_add' => $user->hasRole('ROLE_SUPER_ADMIN'),
                    'allow_delete' => $user->hasRole('ROLE_SUPER_ADMIN')
                ))
                ->end()
                ->end()
                ->tab('kreatys_cms.admin.parametres.form.tab.admin')
                ->with('kreatys_cms.admin.parametres.form.titre.theme', array(
                    'class' => 'col-md-3'
                ))
                ->add('theme_color', 'choice', array(
                    'label' => 'kreatys_cms.admin.parametres.theme_color.label',
                    'choices' => array(
                        'default' => 'kreatys_cms.admin.parametres.theme_color.default',
                        'blue' => 'kreatys_cms.admin.parametres.theme_color.blue',
                        'red' => 'kreatys_cms.admin.parametres.theme_color.red',
                        'orange' => 'kreatys_cms.admin.parametres.theme_color.orange',
                        'teal' => 'kreatys_cms.admin.parametres.theme_color.teal',
                    )
                ))
                ->end()
                ->end()
        ;
    }

    protected function configureShowFields(\Sonata\AdminBundle\Show\ShowMapper $filter) {
        $filter
                ->with('kreatys_cms.admin.parametres.show.titre.general', array(
                    'class' => 'col-md-6'
                ))
                ->add('logo')
                ->add('logo_footer')
                ->end()
                
                
                ->with('kreatys_cms.admin.parametres.show.titre.seo', array(
                    'class' => 'col-md-6'
                ))
                ->add('seo.meta_title', null, array(
                    'label' => 'kreatys_cms.admin.parametres.seo.meta_title'
                ))
                ->add('seo.meta_keywords', null, array(
                    'label' => 'kreatys_cms.admin.parametres.seo.meta_keywords'
                ))
                ->add('seo.meta_description', null, array(
                    'label' => 'kreatys_cms.admin.parametres.seo.meta_description'
                ))
                ->add('seo.footer', null, array(
                    'label' => 'kreatys_cms.admin.parametres.seo.footer'
                ))
                ->add('seo.google_analitics', null, array(
                    'label' => 'kreatys_cms.admin.parametres.seo.googleAnalitics'
                ))
                ->end()
                
                
                ->with('kreatys_cms.admin.parametres.show.titre.autres', array(
                    'class' => 'col-md-6'
                ))
                ->add('autres', null, array(
                    'label' => false,
                    'template' => 'KreatysCmsBundle:Admin:CRUD/show__array_parametre.html.twig'
                ))
                ->end()
                
                
                ->with('kreatys_cms.admin.parametres.show.titre.admin', array(
                    'class' => 'col-md-6'
                ))
                ->add('theme_color', null, array(
                    'label' => 'kreatys_cms.admin.parametres.theme_color.label'
                ))
                ->end()
        ;
    }

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection) {
        $collection->clearExcept(array('edit', 'show'));
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate($object) {
//        dump($object);
        
        // copie du css
        $css = __DIR__ . '/../../../../src/Kreatys/AdminBundle/Resources/public/css/theme-color/css/' . $object->getThemeColor() . '.css';
        $cssAdmin = __DIR__ . '/../../../../src/Kreatys/AdminBundle/Resources/public/css/admin_color.css';
        $copyCss = copy($css, $cssAdmin);
        
        // copie du logo
        $logo = __DIR__ . '/../../../../src/Kreatys/AdminBundle/Resources/public/css/theme-color/img/logo-' . $object->getThemeColor() . '.png';
        $logoAdmin = __DIR__ . '/../../../../src/Kreatys/AdminBundle/Resources/public/img/logo-kreatys.png';
        $copyLogo = copy($logo, $logoAdmin);
        
        // copie pour icheck
        $icheck = __DIR__ . '/../../../../src/Kreatys/AdminBundle/Resources/public/css/theme-color/img/icheck-' . $object->getThemeColor() . '.png';
        $icheckAdmin = __DIR__ . '/../../../../src/Kreatys/AdminBundle/Resources/public/css/iCheck/blue.png';
        $copyIcheck = copy($icheck, $icheckAdmin);
        
//        dump($copyCss);
//        dump($copyLogo);
//        exit;
    }
    
    private function getUser() {
        return $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
    }
}
