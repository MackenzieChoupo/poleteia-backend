<?php

namespace Kreatys\CmsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface {

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kreatys_cms');

        $rootNode
                ->children()
                    ->arrayNode('templates')
                        ->children()
                            ->scalarNode('layout')->defaultValue('KreatysCmsBundle::layout.html.twig')->end()
                            ->scalarNode('show')->defaultValue('KreatysCmsBundle:Page:show.html.twig')->end()
                            ->scalarNode('show_without_layout')->defaultValue('KreatysCmsBundle:Page:show_without_layout.html.twig')->end()
                            ->scalarNode('show_breadcrumb')->defaultValue('KreatysCmsBundle:Page:show_breadcrumb.html.twig')->end()
                            ->scalarNode('block_container')->defaultValue('KreatysCmsBundle:Block:block_container.html.twig')->end()
                            ->scalarNode('block_container_anchor')->defaultValue('KreatysCmsBundle:Block:block_container_anchor.html.twig')->end()
                            ->scalarNode('block_raw_content')->defaultValue('KreatysCmsBundle:Block:block_raw_content.html.twig')->end()
                            ->scalarNode('block_title_text')->defaultValue('KreatysCmsBundle:Block:block_title_text.html.twig')->end()
                            ->scalarNode('block_contact_form')->defaultValue('KreatysCmsBundle:Block:block_form_contact.html.twig')->end()
                            ->scalarNode('block_icon_text')->defaultValue('KreatysCmsBundle:Block:block_icon_text.html.twig')->end()
                            ->scalarNode('block_image')->defaultValue('KreatysCmsBundle:Block:block_image.html.twig')->end()
                            ->scalarNode('block_gallery')->defaultValue('KreatysCmsBundle:Block:block_gallery.html.twig')->end()
                            ->scalarNode('block_gallery_slideshow')->defaultValue('KreatysCmsBundle:Block:Gallery/slideshow.html.twig')->end()
                            ->scalarNode('block_gallery_slideshow_home')->defaultValue('KreatysCmsBundle:Block:Gallery/slideshow_home.html.twig')->end()
                            ->scalarNode('block_map')->defaultValue('KreatysCmsBundle:Block:block_map.html.twig')->end()
                            ->scalarNode('block_list_icon')->defaultValue('KreatysCmsBundle:Block:block_list_icon.html.twig')->end()
                            ->scalarNode('block_titre')->defaultValue('KreatysCmsBundle:Block:block_titre.html.twig')->end()
                            ->scalarNode('block_video')->defaultValue('KreatysCmsBundle:Block:block_video.html.twig')->end()
                            ->scalarNode('block_menu')->defaultValue('KreatysCmsBundle:Block:block_menu.html.twig')->end()
                        ->end()
                    ->end()
                    ->arrayNode('block')
                        ->children()
                            ->scalarNode('default')->defaultValue('kreatys_cms.block.service.title_text')->end()
                            ->scalarNode('container')->defaultValue('kreatys_cms.block.service.container')->end()
                            ->scalarNode('container_anchor')->defaultValue('kreatys_cms.block.service.container_anchor')->end()
                            ->arrayNode('security')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->arrayNode('ROLE_ADMIN')->defaultValue(array(
                                            'kreatys_cms.block.service.container',
                                            'kreatys_cms.block.service.raw_content',
//                                            'kreatys_cms.block.service.title_text',
                                            'kreatys_cms.block.service.contact_form',
//                                            'kreatys_cms.block.service.icon_text',
                                            'kreatys_cms.block.service.image',
                                            'kreatys_cms.block.service.gallery',
                                            'kreatys_cms.block.service.map',
//                                            'kreatys_cms.block.service.list_icon',
                                            'kreatys_cms.block.service.titre'
                                            ))->prototype('scalar')->end()
                                    ->end()
                                    ->arrayNode('ROLE_SUPER_ADMIN')->defaultValue(array())->prototype('scalar')->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('settings')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->arrayNode('text_align')->defaultValue(array(
                                                'text-left' => 'À gauche',
                                                'text-center' => 'Au centre',
                                                'text-right' => 'À droite',
                                            ))->prototype('scalar')->end()
                                    ->end()
                                    ->arrayNode('background')->defaultValue(array(
                                                'bg-color-light' => 'Clair',
                                                'bg-color-dark' => 'Foncé',
                                                'bg-color-darker' => 'Sombre',
                                                'bg-color-sea' => 'Mer',
                                                'bg-color-aqua' => 'Eau',
                                                'bg-color-red' => 'Rouge',
                                                'bg-color-blue' => 'Bleu',
                                                'bg-color-dark-blue' => 'Bleu foncé',
                                                'bg-color-grey' => 'Gris',
                                                'bg-color-light-grey' => 'Gris clair',
                                                'bg-color-green' => 'Vert',
                                                'bg-color-green1' => 'Vert (2)',
                                                'bg-color-light-green' => 'Vert clair',
                                                'bg-color-brown' => 'Marron',
                                                'bg-color-orange' => 'Orange',
                                                'bg-color-purple' => 'Violet'
                                            ))->prototype('scalar')->end()
                                    ->end()
                                    ->arrayNode('padding_top')->defaultValue(array(
                                                'padding-top-5' => '5px',
                                                'padding-top-10' => '10px',
                                                'padding-top-15' => '15px',
                                                'padding-top-20' => '20px',
                                                'padding-top-25' => '25px',
                                                'padding-top-30' => '30px',
                                                'padding-top-35' => '35px',
                                                'padding-top-40' => '40px',
                                                'padding-top-45' => '45px',
                                                'padding-top-50' => '50px',
                                                'padding-top-55' => '55px',
                                                'padding-top-60' => '60px',
                                                'padding-top-100' => '100px'
                                            ))->prototype('scalar')->end()
                                    ->end()
                                    ->arrayNode('padding_bottom')->defaultValue(array(
                                                'padding-bottom-5' => '5px',
                                                'padding-bottom-10' => '10px',
                                                'padding-bottom-15' => '15px',
                                                'padding-bottom-20' => '20px',
                                                'padding-bottom-25' => '25px',
                                                'padding-bottom-30' => '30px',
                                                'padding-bottom-35' => '35px',
                                                'padding-bottom-40' => '40px',
                                                'padding-bottom-45' => '45px',
                                                'padding-bottom-50' => '50px',
                                                'padding-bottom-55' => '55px',
                                                'padding-bottom-60' => '60px',
                                                'padding-bottom-100' => '100px'
                                            ))->prototype('scalar')->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('contact')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->arrayNode('form')
                                        ->addDefaultsIfNotSet()     
                                        ->children()
                                            ->scalarNode('type')->end()
                                            ->scalarNode('handler')->end()
                                        ->end()
                                    ->end()
                                    ->arrayNode('javascripts')->defaultValue(array())->prototype('scalar')->end()->end()
                                    ->arrayNode('stylesheets')->defaultValue(array())->prototype('scalar')->end()->end()
                                ->end()
                            ->end()
//                            ->arrayNode('disabled')->defaultValue(array(
//                                            'kreatys_cms.block.service.container',
//                                            'kreatys_cms.block.service.raw_content',
//                                            'kreatys_cms.block.service.title_text',
//                                            'kreatys_cms.block.service.contact_form',
//                                            'kreatys_cms.block.service.icon_text',
//                                            'kreatys_cms.block.service.image',
//                                            'kreatys_cms.block.service.gallery',
//                                            'kreatys_cms.block.service.map',
//                                            'kreatys_cms.block.service.title_list_icon'
//                                            ))->prototype('scalar')->end()
//                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('page')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->arrayNode('security')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->arrayNode('roles')->defaultValue(array(
                                            'ROLE_USER' => 'avec un compte client'
                                            ))->prototype('scalar')->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('options')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->arrayNode('titre')->defaultValue(array(
                                            'input' => false,
                                            'afficher' => false
                                            ))->prototype('scalar')->end()
                                    ->end()
                                    ->arrayNode('fil_ariane')->defaultValue(array(
                                            'lien' => false,
                                            'afficher' => false
                                            ))->prototype('scalar')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('menu')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->arrayNode('config')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('ancre')->defaultValue(false)->end()
                                    ->scalarNode('sous_titre')->defaultValue(false)->end()
                                    ->scalarNode('multi_level')->defaultValue(false)->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                
        ;

        return $treeBuilder;
    }

}
