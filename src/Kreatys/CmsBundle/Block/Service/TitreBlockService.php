<?php

namespace Kreatys\CmsBundle\Block\Service;

use Symfony\Component\HttpFoundation\Request;
use Kreatys\CmsBundle\Block\BaseBlockService;
use Kreatys\CmsBundle\Entity\Block;

/**
 * Description of TitreBlockService
 *
 * @author remi
 */
class TitreBlockService extends BaseBlockService {

    public function init($front = false) {

        $this->removeSettings(array('text_align'));

        $this
                ->addContent('titre', 'text', array(
                    'label' => 'Titre',
                    'data' => 'Titre à remplacer'
                ))
        ;
        $this->addSetting('text_align', 'choice', array(
            'label' => 'Alignement du texte',
            'choices' => array(
                'headline' => 'À gauche',
                'headline-center' => 'Au centre',
                'headline-right' => 'À droite',
            )
        ));
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
        return 'Titre (seul)';
    }

    public function getTemplate() {
        return $this->container->getParameter('block_titre');
    }

    public function getJavascripts($media = null) {
        return array(
//            'bundles/chprodcore/'
        );
    }

    public function getStylesheets($media = null) {
        return array(
//            'bundles/chprodcore/',
        );
    }

}
