<?php

namespace Kreatys\CmsBundle\Manager;

use Doctrine\ORM\EntityManager;
use Kreatys\CmsBundle\Entity\Page;
use Kreatys\CmsBundle\Entity\Snapshot;
use Kreatys\CmsBundle\Entity\Block;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CmsPageManager {

    /**
     * @var \Doctrine\ORM\EntityManager 
     */
    protected $em;

    /**
     * @var \Kreatys\CmsBundle\Manager\CmsSnapshotManager 
     */
    protected $cmsSnapshotManager;
    
    /*
     * @var Symfony\Component\DependencyInjection\ 
     */
    protected $container;

    /**
     * @var string 
     */
    protected $defaultLocale;

    public function __construct(EntityManager $em, CmsSnapshotManager $cmsSnapshotManager, ContainerInterface $container, $defaultLocale) {
        $this->em = $em;
        $this->cmsSnapshotManager = $cmsSnapshotManager;
        $this->container = $container;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * Publie une page (créer un snapshot à partir d'une page)
     * @param Page $page
     * @return Snapshot
     */
    public function publish(Page $page, $force = false) {
        if ($page->getEdited() || $force) {
            $snapshot = $this->cmsSnapshotManager->getSnapshotByPage($page);

            $snapshot->setName($page->getName());
            $snapshot->setSlug($page->getSlug());
            $snapshot->setUrl($page->getUrl());
            $snapshot->setTitle($page->getTitle());
            $snapshot->setMetaTitle($page->getMetaTitle());
            $snapshot->setMetaKeywords($page->getMetaKeywords());
            $snapshot->setMetaDescription($page->getMetaDescription());
            $snapshot->setGoogleAnalitics($page->getGoogleAnalitics());
            $snapshot->setStylesheets($page->getStylesheets());
            $snapshot->setJavascripts($page->getJavascripts());
            $snapshot->setViewTitle($page->getViewTitle());
            $snapshot->setBreadcrumb($page->getBreadcrumb());
            $snapshot->setRedirect($page->getRedirect());
            $snapshot->setRouteName($page->getRouteName());
            $snapshot->setRouteOptions($page->getRouteOptions());
            $snapshot->setRouteRequirements($page->getRouteRequirements());
            $snapshot->setConnexion($page->getConnexion());
            $snapshot->setRedirectConnexion($page->getRedirectConnexion());
            $snapshot->setRedirectSession($page->getRedirectSession());
            $snapshot->setBreadcrumbLink($page->getBreadcrumbLink());

            $snapshot->setEnabled(true);
            
            $this->em->persist($snapshot);
            $this->em->persist($page);
            $this->em->flush();

            $this->cmsSnapshotManager->preRender($snapshot);

            $page->setEdited(false);
            $page->setEnabled(true);

            $snapshot->setLocale($page->getLocale());

            $this->em->persist($snapshot);
            $this->em->persist($page);
            $this->em->flush();

            return $snapshot;
        }
    }

    public function fixUrl(Page $page) {
        $page->setSlug($this->slugify($page->getName()));

        $suffixe = $page->getUrlSuffixe();

        if (empty($page->getCustomUrl())) {
            if ($page->getParent()) {
//                $page->setSlug($this->slugify($page->getName()));

                if ($page->getParent()->getUrl() == '/') {
                    $base = '/';
                } elseif (substr($page->getParent()->getUrl(), -1) != '/') {
                    $base = $page->getParent()->getUrl() . '/';
                } else {
                    $base = $page->getParent()->getUrl();
                }

                $page->setUrl($base . $page->getSlug() . $suffixe);

//                if (!$page->getSpeciale()) {
//                    $this->createRouteName($page);
//                }
            } else {
                $page->setSlug(null);
                $page->setUrl('/' . $page->getSlug());
                $page->setRouteName('kreatys_cms_home');
            }
        } else {
            $page->setUrl($page->getCustomUrl() . $suffixe);
//            if (!$page->getSpeciale()) {
//                $this->createRouteName($page);
//            }
        }

        $this->em->persist($page);

        if (!empty($page->getChildren())) {
            foreach ($page->getChildren() as $child) {
                $this->fixUrl($child);
            }
        }
    }

    private function createRouteName(Page $page) {
//        if ($page->getLocale() == $this->defaultLocale && !empty($page->getId())) {
//            $slug = $this->getSlugsParents($page) . '-' . $page->getSlug();
//            $routeName = 'kreatys_cms_' . str_replace('-', '_', $slug);
//            $routeName = 'kreatys_cms_' . $page->getId();
//            $page->setRouteName($routeName);
//        }
    }

    private function getSlugsParents(Page $page) {
        $slug = $page->getParent()->getSlug();
        if (!empty($page->getParent()->getParent())) {
            $slugParent = $this->getSlugsParents($page->getParent());
            if($slugParent) {
                $slug = $slugParent . '-' . $slug;
            }
        }
        return $slug;
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

    /**
     * Récup une page
     * @param int $id
     * @return Page
     */
    public function getPageById($id) {
        return $this->em->getRepository('KreatysCmsBundle:Page')->find($id);
    }

    /**
     * Affecte le bloc master à la page
     * @param Page $page
     */
    public function addMasterblock(Page $page) {
        $block = new Block();
        $block->setName('master');
        $block->setType($this->container->getParameter('block_conteneur'));
        $block->setPage($page);
        $block->setEnabled(true);
        $this->em->persist($block);
        $this->em->flush();
        
        $page->addBlock($block);
    }

}
