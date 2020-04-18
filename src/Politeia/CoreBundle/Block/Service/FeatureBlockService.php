<?php

namespace Politeia\CoreBundle\Block\Service;

use Symfony\Component\HttpFoundation\Request;
use Kreatys\CmsBundle\Block\BaseBlockService;
use Kreatys\CmsBundle\Entity\Block;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Description of FeatureBlockService
 *
 * @author remi
 */
class FeatureBlockService extends BaseBlockService {

    public function init($front = false) {
        $this->removeSettings(array('text_align', 'background'));
        $this
                ->addContent('titre', 'text', array(
                    'label' => 'Titre',
//                    'data' => 'Titre à remplacer'
                ))
                ->addContent('text', 'text', array(
                    'label' => 'Texte',
//                    'data' => 'Texte à remplacer'
                ))
                ->addContent('icon', 'choice', array(
                    'label' => 'Icon',
                    'attr' => array(
                        'placeholder' => 'Icon'
                    ),
                    'choices' => $this->getIcons(),
                    'attr' => array(
                        'class' => 'select-fa-icon'
                    )
                ))
                ->addContent('dispo', 'choice', array(
                    'label' => 'Disposition',
                    'choices' => array(
                        'service' => 'Centré',
                        'feature-left' => 'Gauche',
                        'feature-right' => 'Droite'
                    )
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
        return 'Features';
    }

    public function getTemplate() {
        return 'PoliteiaCoreBundle:Block:block_feature.html.twig';
    }

    public function getJavascripts($media = null) {
        return array(
        );
    }

    public function getStylesheets($media = null) {
        return array(
        );
    }

    private function getIcons() {
        $icons = array();

        $content = file_get_contents(__DIR__ . '/../../../../../web/bundles/politeiacore/plugins/font-awesome/css/font-awesome.css');
        preg_match_all('/^\.fa-([a-z0-9-]+):before \{/m', $content, $icons);
        
        sort($icons[1]);
        
        $data = array();
        foreach($icons[1] as $icon) {
            $data[$icon] = $icon;
        }
        
        return $data;
    }

}
