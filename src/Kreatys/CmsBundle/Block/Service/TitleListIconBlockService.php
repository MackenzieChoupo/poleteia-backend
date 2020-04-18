<?php

namespace Kreatys\CmsBundle\Block\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Kreatys\CmsBundle\Block\BaseBlockService;
use Kreatys\CmsBundle\Entity\Block;

class TitleListIconBlockService extends BaseBlockService {

    public function init($front = false) {
        $this
                ->addContent('title', 'text', array(
                    'label' => 'Titre',
                    'data' => 'Titre à remplacer'
                ))
//            ->addContent('text', 'sonata_type_collection', array(
//                'label' => 'Liste',
//                'attr' => array(
//                    'rows' => '10'
//                ),                    
//                'data' => 'Texte à remplacer'                
//            ))             
                ->addContent('list', 'sonata_type_collection', array(
                    'type_options' => array(
                        // Prevents the "Delete" option from being displayed
                        'delete' => true,
                        'delete_options' => array(
                            // You may otherwise choose to put the field but hide it
                            'type' => 'hidden',
                            // In that case, you need to fill in the options as well
                            'type_options' => array(
                                'mapped' => false,
                                'required' => false,
                            )
                        )
                    ),
                    'data' => array(
                        array(
                            'icon' => 'home',
                            'text' => '9 chemin du Vieux Chêne, 38240 Meylan',
                            'url' => ''
                        ),
                        array(
                            'icon' => 'envelope',
                            'text' => 'contact@tfreelances.com',
                            'url' => 'mailto:contact@tfreelances.com'
                        ),
                        array(
                            'icon' => 'phone',
                            'text' => '(+33) 4.76.04.84.61',
                            'url' => ''
                        ),
                        array(
                            'icon' => 'globe',
                            'text' => 'kreatys.com',
                            'url' => 'http://www.kreatys.com'
                        ),
                    )
                        ), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'position',
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
        return 'Titre et Liste avec Icon';
    }

    public function getTemplate() {
        return $this->container->getParameter('block_title_list_icon');
    }

}
