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
 * Description of MapBlockService
 *
 * @author remi
 */
class MapBlockService extends BaseBlockService {
    
    public function getTemplate() {
        return $this->container->getParameter('block_map');
    }

    public function init($front = false) {
        $this
                ->addSetting('adresse', 'textarea', array(
                    'required' => true,
                    'label' => 'Adresse'
                    )
                )
        ;
        $this->removeSettings(array(
            'background',
            'text_align'
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
        return 'Google Map';
    }

}
