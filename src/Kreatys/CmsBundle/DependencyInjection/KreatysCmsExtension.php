<?php

namespace Kreatys\CmsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class KreatysCmsExtension extends Extension {

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        
        $container->setParameter('base_layout', $config['templates']['layout']);
        $container->setParameter('show_layout', $config['templates']['show']);
        $container->setParameter('show_without_layout', $config['templates']['show_without_layout']);
        $container->setParameter('show_breadcrumb', $config['templates']['show_breadcrumb']);
        $container->setParameter('block_container', $config['templates']['block_container']);
        $container->setParameter('block_container_anchor', $config['templates']['block_container_anchor']);
        $container->setParameter('block_raw_content', $config['templates']['block_raw_content']);
        $container->setParameter('block_title_text', $config['templates']['block_title_text']);
        $container->setParameter('block_contact_form', $config['templates']['block_contact_form']);
        $container->setParameter('block_icon_text', $config['templates']['block_icon_text']);
        $container->setParameter('block_image', $config['templates']['block_image']);
        $container->setParameter('block_gallery', $config['templates']['block_gallery']);
        $container->setParameter('block_gallery_slideshow', $config['templates']['block_gallery_slideshow']);
        $container->setParameter('block_map', $config['templates']['block_map']);
        $container->setParameter('block_list_icon', $config['templates']['block_list_icon']);
        $container->setParameter('block_titre', $config['templates']['block_titre']);
        $container->setParameter('block_video', $config['templates']['block_video']);
        $container->setParameter('block_menu', $config['templates']['block_menu']);
        
        $container->setParameter('block_default', $config['block']['default']);
        $container->setParameter('block_conteneur', $config['block']['container']);
        $container->setParameter('block_conteneur_anchor', $config['block']['container_anchor']);
        
        $container->setParameter('block_security', $config['block']['security']);
        $container->setParameter('block_settings', $config['block']['settings']);
//        $container->setParameter('block_disabled', $config['block']['disabled']);
        $container->setParameter('page_security', $config['page']['security']['roles']);
        $container->setParameter('page_options', $config['page']['options']);
        
        $container->setParameter('menu_config_ancre', $config['menu']['config']['ancre']);
        $container->setParameter('menu_config_sous_titre', $config['menu']['config']['sous_titre']);
        $container->setParameter('menu_config_multi_level', $config['menu']['config']['multi_level']);
        
        $container->setParameter('block_contact_form_type', isset($config['block']['contact']['form']['type']) ? $config['block']['contact']['form']['type'] : null);
        $container->setParameter('block_contact_form_handler', isset($config['block']['contact']['form']['handler']) ? $config['block']['contact']['form']['handler'] : null);
        $container->setParameter('block_contact_javascripts', isset($config['block']['contact']['javascripts']) ? $config['block']['contact']['javascripts'] : null);
        $container->setParameter('block_contact_stylesheets', isset($config['block']['contact']['stylesheets']) ? $config['block']['contact']['stylesheets'] : null);
    }

}
