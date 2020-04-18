<?php

namespace Kreatys\CmsBundle\Block\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Kreatys\CmsBundle\Block\BaseBlockService;
use Kreatys\CmsBundle\Entity\Block;

/**
 * Description of IconTextBlockService
 *
 * @author remi
 */
class IconTextBlockService extends BaseBlockService {

    public function getTemplate() {
        return $this->container->getParameter('block_icon_text');
    }

    public function init($front = false) {
        $this
                ->addContent('icon', 'hidden', array(
                    'required' => true,
                    'label' => 'Icon',
                    'attr' => array(
                        'class' => 'choice-icon'
                    ),
                    'data' => 'circle'
                        )
                )
                ->addContent('titre', 'text', array(
                    'required' => true,
                    'label' => 'Titre',
                    'data' => 'Titre à remplacer'
                        )
                )
                ->addContent('text', 'textarea', array(
                    'required' => true,
                    'label' => 'Texte',
                    'data' => 'Texte à remplacer'
                        )
                )
        ;
        $this->clearSettings();
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
        return 'Icon et Texte';
    }
    
    public function hasProcessRequest() {
        return true;
    }
    
    public function processRequest(Request $request, Block $block) {
        
    }

}
