<?php

namespace Kreatys\CmsBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class RouteExtension extends \Twig_Extension
{
    /**
	 * @var \Symfony\Component\DependencyInjection\ContainerInterface 
	 */
	protected $container;
    
    /**
	 * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}
    
    /**
     * @return array
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('route_exist', array($this, 'routeExist'), array()),
            new \Twig_SimpleFunction('change_locale_route', array($this, 'changeLocaleRoute'), array()),
        );
    }
    
    /**
     * 
     * @param string $name
     * @param string $locale
     * @return string
     */
    public function changeLocaleRoute($name, $locale)
    {   
        return preg_replace('#\.[a-z]{2}$#', '', $name).".".$locale;        
    }
    
    /**
     * 
     * @param string $name
     * @param string $locale
     * @return boolean
     */
    public function routeExist($name, $locale)
    {   
        $router = $this->container->get('router');      
        if('' !== $locale) {
            return (null === $router->getRouteCollection()->get($name.".".$locale)) ? false : true;
        } else {
            return (null === $router->getRouteCollection()->get($name.".".$router->getContext()->getParameter('_locale'))) ? false : true;
        }   
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'route_extension';
    }

}
