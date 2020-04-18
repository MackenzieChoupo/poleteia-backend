<?php

namespace Kreatys\CmsBundle\Block\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Kreatys\CmsBundle\Block\BaseBlockService;
use Kreatys\CmsBundle\Entity\Block;

class TitleTextBlockService extends BaseBlockService {

     public function init($front = false)
     {
         $this
            ->addContent('title', 'text', array(
                'label' => 'Titre',
                'data' => 'Titre à remplacer'
            ))
            ->addContent('text', 'textarea', array(
                'label' => 'Texte',
                'attr' => array(
                    'rows' => '10'
                ),                    
                'data' => 'Texte à remplacer'                
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
        return 'Titre et Texte';
    }

    public function getTemplate()
    {
        return $this->container->getParameter('block_title_text');
    }

}
