<?php

namespace Politeia\CoreBundle\Block\Service;

use Symfony\Component\HttpFoundation\Request;
use Kreatys\CmsBundle\Block\BaseBlockService;
use Kreatys\CmsBundle\Entity\Block;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Description of ButtonBlockService
 *
 * @author remi
 */
class ButtonBlockService extends BaseBlockService {

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
                    ->addContent('label', 'text', array(
                        'label' => 'Label',
//                        'data' => 'Label du bouton'
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
            ;
            $this->addSetting('color', 'choice', array(
                'label' => 'Couleur',
                'choices' => array(
                    'btn' => 'Transparence',
                    'btn btn-danger' => 'Fond rouge'
                )
            ));
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
        return 'Bouton (lien)';
    }

    public function getTemplate() {
        return 'PoliteiaCoreBundle:Block:block_button.html.twig';
    }

    public function buildEditContentsForm(FormMapper $formMapper, Block $block) {
        $contents = $block->getContents();

        if (isset($contents['label']) && $contents['label'] === 'Texte à remplacer') {
            $contents['label'] = '';
        }

        $block->setContents($contents);

        parent::buildEditContentsForm($formMapper, $block);
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
