<?php

namespace Kreatys\CmsBundle\Block\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Kreatys\CmsBundle\Block\BaseBlockService;
use Kreatys\CmsBundle\Entity\Block;

class RawContentBlockService extends BaseBlockService {

    public function init($front = false)
    {
        $this
            ->addContent('content', 'textarea', array(
                'label' => 'Texte',
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
        return 'Texte (seul)';
    }

    public function getTemplate()
    {
        return $this->container->getParameter('block_raw_content');
    }

}
