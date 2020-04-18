<?php

namespace Kreatys\CmsBundle\Block\Service;

use Symfony\Component\HttpFoundation\Request;
use Kreatys\CmsBundle\Block\BaseBlockService;
use Kreatys\CmsBundle\Entity\Block;

/**
 * Description of MenuBlockService
 *
 * @author remi
 */
class MenuBlockService extends BaseBlockService {

    public function init($front = false) {

        $this->removeSettings(array('text_align', 'background', 'padding_top', 'padding_bottom'));
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
        return 'Header (Menu)';
    }
    
    public function hasProcessRequest() {
        return true;
    }

    public function getTemplate() {
        return $this->container->getParameter('block_menu');
    }

    public function getJavascripts($media = null) {
        return array(
            
        );
    }

    public function getStylesheets($media = null) {
        return array(
            
        );
    }

}
