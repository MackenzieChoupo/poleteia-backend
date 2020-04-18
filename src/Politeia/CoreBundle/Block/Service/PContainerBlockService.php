<?php

namespace Politeia\CoreBundle\Block\Service;

use Kreatys\CmsBundle\Block\Service\ContainerBlockService;

/**
 * Description of PContainerBlockService
 *
 * @author remi
 */
class PContainerBlockService extends ContainerBlockService {
    
    const TYPE_UN_CINQ_UN_SEPT = '5-7';

    public function init($front = false) {
        parent::init($front);
        $this->removeSettings(array('layout', 'layout_offset', 'responsive'));
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
                        self::TYPE_UN_CINQ_UN_SEPT => '5/12 - 7/12',
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

}
