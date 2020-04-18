<?php

namespace Kreatys\CmsBundle\Block\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Kreatys\CmsBundle\Block\BaseBlockService;
use Kreatys\CmsBundle\Entity\Block;
use Doctrine\ORM\EntityManagerInterface;

class GalleryBlockService extends BaseBlockService {

    private $template = 'block_gallery';

    public function init($front = false) {
        
        $this
            ->addSetting('interval', 'choice', array(
                'required' => false,
                'label' => 'Interval (en secondes) :',
                'choices' => array(
                    1000 => '1 seconde',
                    2000 => '2 secondes',
                    3000 => '3 secondes',
                    5000 => '5 secondes',
                    10000 => '10 secondes',
                ),
                'multiple' => false,
                'expanded' => false,
            ))
            ->addSetting('show_controls', 'choice', array(
                'required' => true,
                'label' => 'Afficher contrôles de navigation :',
                'choices' => array(
                    1 => 'Oui',
                    0 => 'Non'
                ),
                'multiple' => false,
                'expanded' => false,
            ))
            ->addSetting('all_page', 'choice', array(
                'required' => true,
                'label' => 'Toute la largeur de la page :',
                'choices' => array(
                    0 => 'Non',
                    1 => 'Oui',
                ),
                'multiple' => false,
                'expanded' => false,
            ))
        ;
        
        $this
            ->addContent('gallery', 'kcms_gallery', array(
                'required' => true,
                'label' => 'Galerie :',
            ))
        ;
        $this->removeSettings('text_align');
    }

    public function render(Block $block, $front = false) {
        $contents = $block->getContents();
        $gallery = $contents['gallery'];
        
        if ($gallery && $this->container->get('doctrine.orm.default_entity_manager')->getRepository('ApplicationSonataMediaBundle:Gallery')->find($gallery->getId()) === null) {
            $gallery = null;
            $contents['gallery'] = null;
        }
        
        if ($gallery) {
            $contents['gallery'] = $this->container->get('doctrine.orm.default_entity_manager')->getReference('ApplicationSonataMediaBundle:Gallery', $gallery->getId());
            if ($front) {
                switch ($gallery->getContext()) {
                    case 'slideshow_home':
                        $this->template = 'block_gallery_slideshow_home';
                        break;
                    case 'slideshow':
                        $this->template = 'block_gallery_slideshow';
                        break;
                    default:
                        $this->template = 'block_gallery';
                }
//                $this->template = 'block_gallery_slideshow';
            }
        }

        return $this->getTemplating()->render($this->getTemplate(), array(
                    'kblock' => $block,
                    'contents' => $contents,
                    'settings' => $block->getSettings(),
                    'front' => $front
        ));
    }

    public function getName() {
        return 'Galerie';
    }

    public function getTemplate() {
        return $this->container->getParameter($this->template);
    }

}
