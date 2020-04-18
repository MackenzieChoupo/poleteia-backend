<?php

namespace Kreatys\CmsBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;


class BlockServiceExtension extends \Twig_Extension
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
            new \Twig_SimpleFunction('kcms_render_block', array($this, 'renderBlock'), array('is_safe' => array('html')))
        );
    }

    /**
     * @return array
     */
    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('block_type_name', array($this, 'blockTypeNameFilter'))
        );
    }

    /**
     * 
     * @param \Kreatys\CmsBundle\Entity\Block $block
     * @param boolean $front
     */
    public function renderBlock(\Kreatys\CmsBundle\Entity\Block $block, $front)
    {      
        $service = $this->container->get('kreatys_cms.manager.block')->get($block);
        $service->callInit($front);
        return $service->render($block, $front);
    }

    /**
     * @param string $type
     * @return string
     */
    public function blockTypeNameFilter($type)
	{
        return $this->container->get('kreatys_cms.manager.block')->getName($type);
    }
    
    public function getGlobals() {
        return array(
            'block_container_type' => $this->container->getParameter('block_conteneur')
        );
    }

        /**
     * @return string
     */
    public function getName() {
        return 'block_service_extension';
    }

}
