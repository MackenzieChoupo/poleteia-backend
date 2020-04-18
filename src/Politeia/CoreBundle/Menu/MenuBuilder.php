<?php

namespace Politeia\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManager;
use Kreatys\CmsBundle\Entity\Menu;
use Symfony\Component\Routing\RouterInterface;

/**
 * Description of MenuBuilder
 *
 * @author remi
 */
class MenuBuilder {

    private $factory;
    private $em;
    private $menu;

    /**
     * @var  RouterInterface
     */
    private $router;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory, EntityManager $em, RouterInterface $router) {
        $this->factory = $factory;
        $this->em = $em;
        $this->router = $router;
    }

    public function createMainMenu(array $options) {
        $repo = $this->em->getRepository('Kreatys\CmsBundle\Entity\Menu');
        $items = $repo->findBy(array('enabled' => true, 'lvl' => 1), array('lft' => 'ASC'));
//        dump($items);
//        exit;
        $this->menu = $this->factory->createItem('root');
        $this->menu->setChildrenAttribute('class', 'nav navbar-nav');

        foreach ($items as $key => $item) {
            $this->addItem($item, $this->menu, false);
        }

//        $menu->addChild('Home', array('uri' => '/'));
        // ... add more children

        return $this->menu;
    }

    private function addItem($item, $menu, $isChild = false) {

        $label = $item->getLabel();
        if (!empty($item->getSousTitre())) {
            $label .= '<small>' . $item->getSousTitre() . '</small>';
        } else if (!$isChild) {
            $label .= '<small>&nbsp;</small>';
        }
        $slugLabel = $this->slugify($item->getLabel());

        if (!empty($item->getPage())) {
            $menu->addChild($slugLabel, array(
                'route' => $item->getPage()->getRouteName(),
                'label' => $label
            ));
        } elseif (!empty($item->getRouteName())) {
            $menu->addChild($slugLabel, array(
                'route' => $item->getRouteName(),
                'label' => $label
            ));
        } elseif (!empty($item->getAncre())) {
            $menu->addChild($slugLabel, array(
                        'uri' => $this->router->generate($item->getAncre()->getBlock()->getPage()->getRouteName()) . '#' . $item->getAncre()->getNom(),
                        'label' => $label
                    ))
                    ->setLinkAttributes(array(
                        'class' => 'page-scroll',
                        'data-nav-section' => $item->getAncre()->getNom()
            ));
        } else {
            $menu->addChild($slugLabel, array(
                'uri' => $item->getUrl(),
                'label' => $label
            ));
        }


        if ($item->getChildren()->count() > 0) {
            $menu[$slugLabel]->setAttribute('dropdown', true);

            foreach ($item->getChildren() as $child) {
                $this->addItem($child, $menu[$slugLabel], true);
            }
        }
    }

    private function slugify($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        $normalizeChars = array(
            'Š' => 'S', 'š' => 's', 'Ð' => 'Dj', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
            'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
            'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U',
            'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
            'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i',
            'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u',
            'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', 'ƒ' => 'f', 'œ' => 'o',
            'ă' => 'a', 'î' => 'i', 'â' => 'a', 'ș' => 's', 'ț' => 't', 'Ă' => 'A', 'Î' => 'I', 'Â' => 'A', 'Ș' => 'S', 'Ț' => 'T',
        );
        $text = strtr($text, $normalizeChars);


        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        return $text;
    }

}
