<?php

namespace Kreatys\CmsBundle\Block\Service;

use Symfony\Component\HttpFoundation\Request;
use Kreatys\CmsBundle\Block\BaseBlockService;
use Kreatys\CmsBundle\Entity\Block;

class VideoBlockService extends BaseBlockService 
{
    public function init($front = false)
    {
        $this->removeSettings(array('text_align'));

        $this
            ->addContent('video', 'kcms_media', array(
                'label' => 'Vidéo',
                'help_block' => 'Format H264/MP4',
            ), array(
                'link_parameters' => array(
                    'provider' => 'kreatys_cms.media.provider.video'
                )
            ))            
        ;
        
        $this
            ->addSetting('controls', 'choice', array(
                'required' => true,
                'label' => 'Afficher contrôles de navigation :',
                'choices' => array(                    
                    0 => 'Non',
                    1 => 'Oui'
                ),
                'multiple' => false,
                'expanded' => false,
            ))
            ->addSetting('autoplay', 'choice', array(
                'required' => true,
                'label' => 'Lecture automatique :',
                'choices' => array(
                    1 => 'Oui',
                    0 => 'Non'
                ),
                'multiple' => false,
                'expanded' => false,
            )) 
        ;
    }

    public function render(Block $block, $front = false)
    {
        return $this->getTemplating()->render($this->getTemplate(), array(
                    'kblock' => $block,
                    'contents' => $block->getContents(),
                    'settings' => $block->getSettings(),
                    'front' => $front
        ));
    }

    public function getName()
    {
        return 'Vidéo H264/MP4';
    }

    public function getTemplate()
    {
        return $this->container->getParameter('block_video');
    }

    
}
