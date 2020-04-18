<?php

namespace Kreatys\CmsBundle\Block;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Kreatys\CmsBundle\Entity\Block;
use Symfony\Component\Security\Core\SecurityContext;

class BlockServiceManager {

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface 
     */
    protected $container;

    /**
     * @var array 
     */
    protected $services;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->services = array();
    }

    /**
     * Ajoute une nouveau service block
     * @param string $name
     * @param \Kreatys\CmsBundle\Block\BlockServiceInterface $service
     */
    public function add($name, BlockServiceInterface $service, $extraParameters = array()) {
        $this->services[$name]['service'] = $service;
        $this->services[$name]['ordre'] = isset($extraParameters['ordre']) ? (int) $extraParameters['ordre'] : 9999;
        $this->services[$name]['group'] = isset($extraParameters['group']) ? $extraParameters['group'] : 'autres';
        $this->services[$name]['grouporder'] = isset($extraParameters['grouporder']) ? $extraParameters['grouporder'] : 9999;
    }

    /**
     * 
     * @param string $id
     * @return \Kreatys\CmsBundle\Block\BlockServiceInterface
     */
    public function getService($id) {
        return $this->load($id);
    }

    /**
     * 
     * @param Block $block
     * @return \Kreatys\CmsBundle\Block\BlockServiceInterface
     */
    public function get(Block $block) {
        $this->load($block->getType());

        return $this->services[$block->getType()]['service'];
    }

    /**
     * 
     * @param string $id
     * @return Kreatys\CmsBundle\Block\BlockServiceInterface
     */
    public function has($id) {
//        var_dump($id);
//        var_dump(array_keys($this->services));
//        var_dump(isset($this->services[$id]) ? true : false);
//        exit;
        return isset($this->services[$id]) ? true : false;
    }

    /**
     * Liste des Type de block dispo
     * @return array
     */
    public function getTypes(SecurityContext $securityContext) {
        $security = array();
//        dump($this->container->getParameter('block_security'));
//        exit;
        foreach ($this->container->getParameter('block_security') as $role => $services) {
            foreach ($services as $service) {
                $security[$service] = $role;
            }
        }
//        $disabledBlocks = $this->container->getParameter('block_disabled');

        uasort($this->services, function($a, $b) {
            if ($a['grouporder'] == $b['grouporder']) {
                if ($a['ordre'] == $b['ordre']) return 0;
                return $a['ordre'] > $b['ordre'] ? 1 : -1;
            }
            return $a['grouporder'] > $b['grouporder'] ? 1 : -1;
        });
//        uasort($this->services, function($a, $b) {
//            if($a['ordre'] == $b['ordre']) return 0;
//            return $a['ordre'] > $b['ordre'] ? 1 : -1;
//        });

//        dump($this->services);
//        exit;

        $list = [];
        foreach ($this->services as $name => $service) {
            if ($service['service'] instanceof BlockServiceInterface) {

                if (isset($security[$name]) && $securityContext->isGranted($security[$name])) { //  AND !in_array($name, $disabledBlocks)
                    $list[$service['group']][$name] = $service['service']->getName();
                }
            }
        }



        return $list;
    }

    /**
     * Retourne le nom du service
     * @param string $type
     * @return string
     */
    public function getName($type) {
        return $this->load($type)->getName();
    }

    /**
     * 
     * @param string $type
     * @return \Kreatys\CmsBundle\Block\BlockServiceInterface
     * @throws \RuntimeException
     */
    private function load($type) {
//        var_dump($type);
        if (!$this->has($type)) {
            throw new \RuntimeException(sprintf('The block service `%s` does not exist', $type));
        }

        if (!$this->services[$type]['service'] instanceof BlockServiceInterface) {
            $this->services[$type]['service'] = $this->container->get($type);
        }

        if (!$this->services[$type]['service'] instanceof BlockServiceInterface) {
            throw new \RuntimeException(sprintf('The service %s does not implement BlockServiceInterface', $type));
        }

        return $this->services[$type]['service'];
    }

}
