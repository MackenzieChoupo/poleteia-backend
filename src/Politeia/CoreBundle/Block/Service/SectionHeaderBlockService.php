<?php

namespace Politeia\CoreBundle\Block\Service;

use Symfony\Component\HttpFoundation\Request;
use Kreatys\CmsBundle\Block\BaseBlockService;
use Kreatys\CmsBundle\Entity\Block;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Description of SectionHeaderBlockService
 *
 * @author remi
 */
class SectionHeaderBlockService extends BaseBlockService {

    public function init($front = false) {
        $this->removeSettings(array('text_align', 'background'));
        $this
                ->addContent('titre', 'text', array(
                    'label' => 'Titre',
                    'data' => 'Titre à remplacer'
                ))
                ->addContent('sousTitre', 'text', array(
                    'label' => 'Sous titre',
                    'data' => 'Sous titre à remplacer'
                ))
        ;
    }

    public function render(Block $block, $front = false) {

        return $this->getTemplating()->render($this->getTemplate(), array(
                    'kblock' => $block,
                    'contents' => $block->getContents(),
                    'settings' => $block->getSettings(),
                    'front' => $front
        ));
    }

    public function hasProcessRequest() {
        return true;
    }

    public function getName() {
        return 'Entête section';
    }

    public function getTemplate() {
        return 'PoliteiaCoreBundle:Block:block_section_header.html.twig';
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
