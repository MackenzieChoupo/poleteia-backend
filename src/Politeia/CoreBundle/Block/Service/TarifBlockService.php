<?php

namespace Politeia\CoreBundle\Block\Service;

use Symfony\Component\HttpFoundation\Request;
use Kreatys\CmsBundle\Block\BaseBlockService;
use Kreatys\CmsBundle\Entity\Block;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Description of TarifBlockService
 *
 * @author remi
 */
class TarifBlockService extends BaseBlockService {

    public function init($front = false) {
        $this->removeSettings(array('text_align', 'background'));
        if (!$front) {
            $pages = array('' => 'Aucune');
            $em = $this->container->get('doctrine')->getEntityManager();
            $allPages = $em->getRepository('Kreatys\CmsBundle\Entity\Snapshot')->findAll();
            foreach ($allPages as $page) {
                $pages[$page->getRouteName()] = $page->getName();
            }
            $ancres = array('' => 'Aucune');
            $allAncres = $em->getRepository('Kreatys\CmsBundle\Entity\Ancre')->findAll();
            foreach ($allAncres as $ancre) {
                $url = $this->container->get('router')->generate($ancre->getBlock()->getPage()->getRouteName()) . '#' . $ancre->getNom();
                $ancres[$url] = $ancre;
            }

            $this
                    ->addContent('titre', 'text', array(
                        'label' => 'Titre',
//                    'data' => 'Titre à remplacer'
                    ))
                    ->addContent('currency', 'text', array(
                        'label' => 'Devise',
                        'required' => false
                    ))
                    ->addContent('price', 'text', array(
                        'label' => 'Prix'
                    ))
                    ->addContent('period', 'text', array(
                        'label' => 'Période',
                        'required' => false
                    ))
                    ->addContent('text', 'textarea', array(
                        'label' => 'Texte',
                        'required' => false,
                        'attr' => array(
                            'rows' => 5
                        )
                    ))
                    ->addContent('label_button', 'text', array(
                        'label' => 'Label du bouton',
                        'required' => false
                    ))
                    ->addContent('lien_page', 'choice', array(
                        'label' => 'Choisir une page',
                        'choices' => $pages,
                        'required' => false,
                    ))
                    ->addContent('lien_ancre', 'choice', array(
                        'label' => 'Ou une ancre',
                        'choices' => $ancres,
                        'required' => false,
                    ))
                    ->addContent('lien_autre', 'url', array(
                        'label' => 'Ou une url',
                        'required' => false,
                    ))
                    ->addContent('lien_target', 'choice', array(
                        'label' => 'Ouvrir la page',
                        'choices' => array(
                            '' => 'Dans le même onglet',
                            '_blank' => 'Dans une nouvelle page'
                        ),
                        'required' => false,
                        'help_block' => 'Ne fonctionne pas pour les ancres'
                    ))
                    ->addContent('color', 'choice', array(
                        'label' => 'Couleur',
                        'choices' => array(
                            '' => 'Gris (default)',
                            'featured' => 'Bleu'
                        ),
                        'required' => false
                    ))
                    ->addContent('disponibleApp', 'choice', array(
                        'label' => 'Disponible sur',
                        'choices' => array(
                            '' => 'Aucun store',
                            'storePlay' => 'App Store ET Google Play',
                            'store' => 'App Store',
                            'play' => 'Google Play',
                        ),
                        'required' => false
                    ))
                    ->addContent('urlAppStore', 'text', array(
                        'label' => 'Url App Store',
                        'required' => false
                    ))
                    ->addContent('urlGooglePlay', 'text', array(
                        'label' => 'Url Google Play',
                        'required' => false
                    ))
            ;
        }
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
        return 'Tarif';
    }

    public function getTemplate() {
        return 'PoliteiaCoreBundle:Block:block_tarif.html.twig';
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
