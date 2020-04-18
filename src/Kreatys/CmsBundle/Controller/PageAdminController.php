<?php

namespace Kreatys\CmsBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Kreatys\CmsBundle\Entity\Page; 
use Kreatys\CmsBundle\Entity\Block;
use Kreatys\CmsBundle\Event\NewRouteEvent;

/**
 * Description of PageAdminController
 *
 * @author remi
 */
class PageAdminController extends Controller {

    /**
     *
     * @var \Kreatys\CmsBundle\Block\BlockServiceManager 
     */
    protected $blockServiceManager;
    
    /**
     * @var \Doctrine\ORM\EntityManager 
     */
    private $em;

    private $previewStylesheets = array();
    private $previewJavascripts = array();
    
    public function listAction() {
        if (!$this->getRequest()->get('filter')) {
            return new RedirectResponse($this->admin->generateUrl('tree'));
        }

        return parent::listAction();
    }

    public function treeAction() {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('tree'), array(
                    'action' => 'list',
                    'form' => $formView,
                    'datagrid' => $datagrid,
                    'csrf_token' => $this->getCsrfToken('sonata.batch'),
                    'default_locale' => $this->getParameter('kernel.default_locale')
        ));
    }

    public function previewAction() {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('VIEW', $object)) {
            throw new AccessDeniedException();
        }

        $this->admin->setSubject($object);

//        dump($object);
//        exit;
        
        $this->blockServiceManager = $this->container->get('kreatys_cms.manager.block');
        $this->previewExtractStylesheetsAndJavascripts($object->getMasterBlock());
        
        $this->previewStylesheets = array_merge($this->previewStylesheets, preg_split("#\r?\n#", $object->getStylesheets()));
        $this->previewStylesheets = array_unique($this->previewStylesheets);
        $object->setStylesheets(implode("\n", $this->previewStylesheets));        
        
        $this->previewJavascripts = array_merge($this->previewJavascripts, preg_split("#\r?\n#", $object->getJavascripts()));
        $this->previewJavascripts = array_unique($this->previewJavascripts);
        $object->setJavascripts(implode("\n", $this->previewJavascripts));
        
        $this->get('twig')->addGlobal('locale', $this->get('request')->get('tl') != "" ? $this->get('request')->get('tl') : $this->container->getParameter('kernel.default_locale'));

        return $this->render($this->admin->getTemplate('preview'), array(
                    'action' => 'preview',
                    'page' => $object,
                    'base_layout' => $this->container->getParameter('base_layout')
        ));
    }
    
    private function previewExtractStylesheetsAndJavascripts($container) {
        foreach($container->getChildren() as $block) {        
            if ($block->getEnabled()) {
                $service = $this->blockServiceManager->get($block);
                $this->previewStylesheets = array_merge($this->previewStylesheets, $service->getStylesheets());
                $this->previewJavascripts = array_merge($this->previewJavascripts, $service->getJavascripts());                
                
                if($block->getType() === $this->getParameter('block_conteneur')) {                    
                    $this->previewExtractStylesheetsAndJavascripts($block);                    
                }
            }
        }
    }
            

    public function composeAction() {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('VIEW', $object)) {
            throw new AccessDeniedException();
        }
        
        if($object->getLocale() != $this->container->getParameter('kernel.default_locale')) {
            $this->initNonDefaultLocaleBlock($object->getMasterBlock(), $this->container->getParameter('kernel.default_locale'));   
        }

        $this->admin->setSubject($object);
        
        $this->get('twig')->addGlobal('locale_switcher_route', 'compose');
        $this->get('twig')->addGlobal('locale', $this->get('request')->get('tl') != "" ? $this->get('request')->get('tl') : $this->container->getParameter('kernel.default_locale'));

        return $this->render($this->admin->getTemplate('compose'), array(
                    'action' => 'compose',
                    'object' => $object,
                    'elements' => $this->admin->getShow(),                   
        ));
    }

    public function publishAction() {
        return $this->publishPage();
    }

    public function publishForceAction() {
        return $this->publishPage(true);
    }

    public function enabledAction(Page $page) {
        $em = $this->getDoctrine()->getEntityManager();

        $page->switchEnabled();

        if ($page->getEnabled()) {
            $page->setEdited(true);
            $cmsPageManager = $this->container->get('kreatys_cms.manager.cms_page');
            $cmsPageManager->publish($page);
        } else {
            $snapshot = $page->getSnapshot();
            if ($snapshot) {
                $em->remove($snapshot);
            }
        }

        $em->persist($page);

        $em->flush();

        return $this->renderJson(array('enabled' => $page->getEnabled()));
    }
    
    private function publishPage($force = false) {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('EDIT', $object)) {
            throw new AccessDeniedException();
        }
        
        $defaultLocale = $this->container->getParameter('kernel.default_locale');
        $locale = $this->get('request')->get('tl') != "" ? $this->get('request')->get('tl') : $defaultLocale;
        
        $this->get('twig')->addGlobal('locale', $locale);

        $cmsPageManager = $this->container->get('kreatys_cms.manager.cms_page');

        $cmsPageManager->publish($object, $force);
        
        // clear cache
        // Modif du 22/08/16 : on clear le cache a chaque publication, car des
        // fois ça marche si la route avait déjà été créer mais non publiée
        // TODO : a revoir
//        $clearCache = false;
//        $collection = $this->container->get('router')->getRouteCollection();
//        $routeName = $object->getRouteName();
//        if($locale != $defaultLocale) {
//            $routeName .= "_".$locale;
//        }
//        if($collection->get($routeName) === null) {
//            $clearCache = true;
//        }
        
        $clearCache = true;
        
        //dump($collection->all(), $routeName, $clearCache); exit();
        if($clearCache) {
            $cmd = new \Symfony\Bundle\FrameworkBundle\Command\CacheClearCommand();
            $cmd->setContainer($this->container);
            $cmd->run(new \Symfony\Component\Console\Input\StringInput(null), new \Symfony\Component\Console\Output\NullOutput());
            $this->get('event_dispatcher')->dispatch(NewRouteEvent::NAME, new NewRouteEvent()); 
        }
        
        if($force) {
            return new RedirectResponse($this->getRequest()->headers->get('referer'));
        } else {
            return new RedirectResponse($this->admin->generateUrl('list', array('id' => $object->getId())));
        }
        
    }
    
    public function duplicateAction(Request $request)
    {
        if($request->getMethod() === 'POST') {
            //$tl = $request->query->get('tl');
            $infos = $request->request->get('duplicate');
            
            $page = $this->admin->getObject($infos['page_id']);            
            
            $newPage = new Page();
            $newPage->setName($infos['name']);
            if($infos['title'] != '') {
                $newPage->setTitle($infos['title']);
            }
            
            $newPage->setParent($page->getParent());
            $newPage->setRedirect($page->getRedirect());
            
            $newPage->setBreadcrumb($page->getBreadcrumb());
            $newPage->setBreadcrumbLink($page->getBreadcrumbLink());
            $newPage->setViewTitle($page->getViewTitle());
            
            $newPage->setGoogleAnalitics($page->getGoogleAnalitics());
            $newPage->setStylesheets($page->getStylesheets());
            $newPage->setJavascripts($page->getJavascripts());
            
            $newPage->setConnexion($page->getConnexion());
            $newPage->setRedirectConnexion($page->getRedirectConnexion());
            $newPage->setRedirectSession($page->getRedirectSession());
            
            $newPage->setEnabled(false);
            $newPage->setEdited(true);
            
            $newPage->setSpeciale(false);
            $newPage->setLocale($this->getParameter('kernel.default_locale'));
            
            $this->admin->create($newPage);

            $newMaster = $newPage->getMasterBlock();
            
            foreach($page->getMasterBlock()->getChildren() as $block) {
                $newBlock = new Block();
                $newMaster->addChild($newBlock);
                $this->duplicateBlock($block, $newBlock);
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($newMaster);
            $em->flush();

            return new RedirectResponse($this->admin->generateUrl('tree'));
            
        } else {
            return new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
    }
    
    private function duplicateBlock(Block $block, Block $newBlock) {
        $newBlock->setPage($block->getPage());
        $newBlock->setContents($block->getContents());
        $newBlock->setSettings($block->getSettings());
        $newBlock->setName($block->getName());
        $newBlock->setType($block->getType());
        $newBlock->setEnabled($block->getEnabled());
        foreach($block->getChildren() as $child) {
            $newChildBlock = new Block();
            $newBlock->addChild($newChildBlock);
            $this->duplicateBlock($child, $newChildBlock);
        }
    }
            
    
    public function listAjaxAction()
    {
        $em = $this->getDoctrine()
                ->getManager()
        ;
        $repo = $em->getRepository('KreatysCmsBundle:Page');
        
        $pages = $repo->getRootNodes();        

        $list = $this->buildListPage($pages[0], $pages[0]->getChildren());
        
        return new JsonResponse(array(
            'list' => $list
        ));
    }
    
    
    private function buildListPage($parent, $children)
    {
        $list = array();
        if($parent->getEnabled()) {
            $p = new \stdClass();
            $p->title = $parent->getName();
            $p->value = $this->generateUrl($parent->getRouteName());
            $list[] = $p;
        }        
        
        foreach($children as $page) {            
            if($page->getEnabled() && $page->getChildren()->count() === 0) {
                $o = new \stdClass();
                $o->title = $page->getName();
                $o->value = $this->generateUrl($page->getRouteName());
                $list[] = $o;
            }
            if($page->getChildren()->count() > 0) {   
                $o = new \stdClass();
                $o->title = $page->getName();
                $o->menu = $this->buildListPage($page, $page->getChildren());
                $list[] = $o;
            }
        } 
        return $list;
    }
    
   
    /**
     * @param \Kreatys\CmsBundle\Entity\Block $container
     */
    private function initNonDefaultLocaleBlock(\Kreatys\CmsBundle\Entity\Block $container, $defaultLocale) {
        foreach($container->getChildren() as $block) {
            $contents = $block->getContents();
            if($block->getContents() == false || (is_array($contents) && count($contents) == 0)) {
                $currentObjectLocale = $block->getLocale();
                $block->setLocale($defaultLocale);
                if($this->em === null) {
                    $this->em = $this->container->get('doctrine.orm.default_entity_manager');
                }
                $this->em->refresh($block);                
                $contents = $block->getContents();               
                $block->setLocale($currentObjectLocale);
                $block->setContents($contents);
                $block->setUpdated(new \DateTime());
                $this->em->persist($block);
                $this->em->flush($block);
            }
            if($block->getType() === $this->getParameter('block_conteneur')) {                    
                $this->initNonDefaultLocaleBlock($block, $defaultLocale);
            }
        }
    }

}
