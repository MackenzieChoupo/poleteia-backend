<?php

namespace Kreatys\CmsBundle\Block\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Kreatys\CmsBundle\Block\BaseBlockService;
use Kreatys\CmsBundle\Entity\Block;

class ImageBlockService extends BaseBlockService {
    
    public function init($front = false) {
        if(!$front) {            
            $pages = array('' => 'Aucune');
            $em = $this->container->get('doctrine')->getEntityManager();
            $allPages = $em->getRepository('Kreatys\CmsBundle\Entity\Snapshot')->findAll();
            foreach ($allPages as $page) {
                $pages[$page->getRouteName()] = $page->getName();
            }
            
            $this
                ->addContent('image', 'kcms_media', array(
                    'required' => true,
                    'label' => 'Image :',
                ))
                ->addContent('lien_page', 'choice', array(
                    'label' => 'Choisir une page', 
                    'choices' => $pages,
                    'required' => false,
                ))
                ->addContent('lien_autre', 'url', array(
                    'label' => 'Ou une url',                 
                    'required' => false,                
                ))
                ->addContent('lien_target', 'choice', array(
                    'label' => 'Ouvrir la page',
                    'choices' => array(
                        '' => 'Dans le même onglet',
                        '_blank' => 'Dans une nouvelle page'
                    ),                   
                    'required' => false,                
                ))
            ;
//            $this->removeSettings('text_align');

            $this->addSetting('options', 'choice', array(
                'label' => 'Option(s) pour l\'image',
                'choices' => array(
                    'img-rounded' => 'Angles arrondis',
                    'img-circle' => 'En cercle',
                    'img-thumbnail' => 'Avec une bordure'
                ),
                'empty_value' => 'Aucune',
                'required' => false
            ));
        }
    }
   
    public function render(Block $block, $front = false) {
        return $this->getTemplating()->render($this->getTemplate(), array(
                    'kblock' => $block,
                    'contents' => $block->getContents(),
                    'settings' => $block->getSettings(),
                    'front' => $front
        ));
    }

    public function getName() {
        return 'Média (image/vidéo)';
    }

    public function getTemplate() {
        return $this->container->getParameter('block_image');
    }

}
