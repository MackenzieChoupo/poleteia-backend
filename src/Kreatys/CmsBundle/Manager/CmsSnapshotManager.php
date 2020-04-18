<?php

namespace Kreatys\CmsBundle\Manager;

use Doctrine\ORM\EntityManager;
use Kreatys\CmsBundle\Repository\SnapshotRepository;
use Kreatys\CmsBundle\Block\BlockServiceManager;
use Kreatys\CmsBundle\Entity\Snapshot;
use Kreatys\CmsBundle\Entity\Page;
use Kreatys\CmsBundle\Entity\Block;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CmsSnapshotManager {

    /**
     * @var \Doctrine\ORM\EntityManager 
     */
    protected $em;

    /**
     * @var \Kreatys\CmsBundle\Repository\SnapshotRepository 
     */
    protected $snapshotRepository;

    /**
     * @var \Kreatys\CmsBundle\Block\BlockServiceManager 
     */
    protected $blockServiceManager;
    
    /*
     * @var Symfony\Component\DependencyInjection\ 
     */
    protected $container;
    
    private $stylesheets = array();
    private $javascripts = array();
    
    public function __construct(EntityManager $em, SnapshotRepository $snapshotRepository, BlockServiceManager $blockServiceManager, ContainerInterface $container) {
        $this->em = $em;
        $this->snapshotRepository = $snapshotRepository;
        $this->blockServiceManager = $blockServiceManager;
        $this->container = $container;
    }

    /**
     * @param Page $page
     * @return Snapshot
     */
    public function getSnapshotByPage(Page $page) {
        $snapshot = $this->snapshotRepository->getByPage($page);
        if ($snapshot === null) {
            $snapshot = new Snapshot();
            $snapshot->setPage($page);
        }
        return $snapshot;
    }

    /**
     * 
     * @param string $slug
     * @return Snapshot
     */
    public function getSnapshotBySlug($slug) {
        return $this->snapshotRepository->getBySlug($slug);
    }

    /**
     * 
     * @param string $url
     * @return Snapshot
     */
    public function getSnapshotByUrl($url) {
        return $this->snapshotRepository->getByUrl($url);
    }

    /**
     * 
     * @param string $url
     * @return Snapshot
     */
    public function getSnapshotByRouteName($routeName) {
        return $this->snapshotRepository->getByRouteName($routeName);
    }

    /**
     * Pre render a page into snapshot
     * @param Snapshot $snapshot
     */
    public function preRender(Snapshot $snapshot) {
        $page = $snapshot->getPage();
        
        $masterBlock = $page->getMasterBlock();
        
        $content = $this->renderContainer($masterBlock);
        
        $snapshot->setContent($content);
        
        $this->stylesheets = array_merge($this->stylesheets, preg_split("#\r?\n#", $snapshot->getStylesheets()));
        $this->stylesheets = array_unique($this->stylesheets);
        $snapshot->setStylesheets(implode("\n", $this->stylesheets));
        
        $this->javascripts = array_merge($this->javascripts, preg_split("#\r?\n#", $snapshot->getJavascripts()));
        $this->javascripts = array_unique($this->javascripts);
        $snapshot->setJavascripts(implode("\n", $this->javascripts));
    }
    
     private function renderContainer(Block $container) {
        $lst = array();
        foreach($container->getChildren() as $block) {        
            if ($block->getEnabled()) {
                $service = $this->blockServiceManager->get($block);
                $detail = array(
                    'id' => $block->getId(),
                    'type' => $block->getType(),
                    'processRequest' => $service->hasProcessRequest()
                );
                
                $this->stylesheets = array_merge($this->stylesheets, $service->getStylesheets());
                $this->javascripts = array_merge($this->javascripts, $service->getJavascripts());                
                
                if($block->getType() === $this->container->getParameter('block_conteneur')) {                    
                    $detail['children'] = $this->renderContainer($block);
                    $detail['content'] = $service->preRender($block);
                } else {
                    if(!$service->hasProcessRequest()) {
                        $detail['content'] = $service->render($block, true);
                    } else {
                        $detail['content'] = '';
                    }
                }
                    
                $lst[] = $detail;                
            }
        }
        
        return $lst;
    }
}
