<?php

namespace Kreatys\CmsBundle\Routing;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Config\Loader\Loader;

/**
 * Description of PageLoader
 *
 * @author remi
 */
class PageLoader extends Loader {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var string 
     */
    protected $defaultLocale;

    /**
     * @var array 
     */
    protected $locales;

    /**
     * 
     * @param EntityManager $em
     * @param type $defaultLocale
     * @param array $locales
     */
    public function __construct(EntityManager $em, $defaultLocale, array $locales) {
        $this->em = $em;
        $this->defaultLocale = $defaultLocale;
        if(count($locales) > 0) { 
            $this->locales = $locales;
        } else {
             $this->locales = array($this->defaultLocale);
        }
    }

    public function load($resource, $type = null) {
        $collection = new RouteCollection();

        $repo = $this->em->getRepository($resource);
        
        foreach ($this->locales as $locale) {
           
            $pages = $repo->findAllByLocale($locale);

            foreach ($pages as $page) {                
                $requirements = $page->getFormatedRouteRequirements();
                $options = $page->getFormatedRouteOptions();
                $url = $page->getUrl();
                if ($url === "" || $url === null ) {
                    $page->setLocale($this->defaultLocale);
                    $this->em->refresh($page);
                    $url = $page->getUrl();
                }                
               
                $route = new Route($url, array(
                    '_controller' => 'KreatysCmsBundle:Page:show',
                    '_locale' => $locale
                        ), $requirements, $options);
                $name = $page->getRouteName() . "." . $locale;
               
                $collection->add($name, $route);
            }
        }

        return $collection;
    }

    public function supports($resource, $type = null) {
        return 'page' === $type;
    }

}
