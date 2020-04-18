<?php

namespace Kreatys\CmsBundle\Block\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Kreatys\CmsBundle\Block\BaseBlockService;
use Kreatys\CmsBundle\Entity\Block;
use Kreatys\CmsBundle\Form\ListIconFieldsType;

class ListIconBlockService extends BaseBlockService {

    public function init($front = false) {
//        $this->removeSettings(array('background', 'text_align', 'padding_top', 'padding_bottom', 'padding_left', 'padding_right'));
        $this->removeSettings(array('text_align'));
        $this
                ->addSetting('list', 'sonata_type_native_collection', array(
                    'label' => 'Liste',
                    'required' => false,
                    'type' => new ListIconFieldsType(),
                    'options' => array(
                        'required' => false,
                        'label' => false
                    ),
                    'allow_add' => true,
                    'allow_delete' => true
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

    public function getName() {
        return 'Liste avec Icon';
    }

    public function getTemplate() {
        return $this->container->getParameter('block_list_icon');
    }

}
