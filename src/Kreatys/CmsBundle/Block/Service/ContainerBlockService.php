<?php

namespace Kreatys\CmsBundle\Block\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Kreatys\CmsBundle\Block\BaseBlockService;
use Kreatys\CmsBundle\Entity\Block;

class ContainerBlockService extends BaseBlockService {

    const TYPE_LIST = 0;
    const TYPE_UN_TIERS_DEUX_TIERS = '4-8'; // 1
    const TYPE_DEUX_TIERS_UN_TIERS = '8-4'; // 2
    const TYPE_TROIS_QUARTS_UN_QUART = '9-3'; // 5
    const TYPE_UN_QUART_TROIS_QUARTs = '3-9'; // 6
    const TYPE_UN_DEMI_UN_DEMI = '6-6'; // 3
    const TYPE_UN_TIERS_UN_TIERS_UN_TIERS = '4-4-4'; // 4
    const TYPE_LARGEUR_TOTAL = '12'; // 7

    public function init($front = false) {
        $this->removeSettings(array('text_align'));
        $this
                ->addSetting('layout', 'choice', array(
                    'label' => 'Disposition :',
                    'choices' => array(
                        //self::TYPE_LIST => 'Normale',
                        self::TYPE_LARGEUR_TOTAL => 'Toute la largeur',
                        self::TYPE_UN_DEMI_UN_DEMI => '1/2 - 1/2',
                        self::TYPE_UN_TIERS_DEUX_TIERS => '1/3 - 2/3',
                        self::TYPE_DEUX_TIERS_UN_TIERS => '2/3 - 1/3',
                        self::TYPE_TROIS_QUARTS_UN_QUART => '3/4 - 1/4',
                        self::TYPE_UN_QUART_TROIS_QUARTs => '1/4 - 3/4',
                        self::TYPE_UN_TIERS_UN_TIERS_UN_TIERS => '1/3 - 1/3 - 1/3'
                    ),
                ))
                ->addSetting('layout_offset', 'choice', array(
                    'label' => 'Espace entre les colonnes :',
                    'required' => false,
                    'choices' => array(
                        '' => 'Aucun',
                        '1' => 'largeur 1',
                        '2' => 'largeur 2',
//                        '3' => 'largeur 3'
                    )
                ))
                ->addSetting('responsive', 'choice', array(
                    'label' => 'Responsive disposition : (jusqu\'à)',
                    'choices' => array(
                        'xs' => 'Extra small device',
                        'sm' => 'Small device',
                        'md' => 'Medium device',
                        'lg' => 'Large device'
                    ),
                    'preferred_choices' => array('md')
                ))
        ;
    }

    public function render(Block $block, $front = false) {
        return $this->getTemplating()->render($this->getTemplate(), array(
                    'kblock' => $block,
                    'contents' => $block->getContents(),
                    'settings' => $block->getSettings(),
                    'front' => $front,
                    'preRender' => false
        ));
    }
    
    public function preRender(Block $block) {
        return $this->getTemplating()->render($this->getTemplate(), array(
                    'kblock' => $block,
                    'contents' => $block->getContents(),
                    'settings' => $block->getSettings(),
                    'front' => true,
                    'preRender' => true
        ));
    }

    public function getName() {
        return 'Conteneur';
    }

    public function getTemplate() {
        return $this->container->getParameter('block_container');
    }

    public function buildEditForm(FormMapper $formMapper, Block $block) {
        $settings = $this->getSettings();
        
        foreach ($settings as $key => $value) {
            if(in_array('layout', $value)) { // on supprime la disposition a l'edition des settings
                $settings[$key][1] = 'hidden';
                $settings[$key][2] = array(
                    'label' => false
                );
            }
        }
        
        $formMapper
                ->add('settings', 'sonata_type_immutable_array', array(
                    'label' => false,
                    'keys' => $settings
        ));
    }

}
