<?php

namespace Kreatys\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Kreatys\CmsBundle\Block\BlockServiceManager;
use Kreatys\CmsBundle\Manager\CmsPageManager;
use Kreatys\CmsBundle\Manager\CmsSnapshotManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;

class PageController extends Controller {

    protected $snapshot;
    protected $blockRepository;
    private $redirect;

    /**
     * Affiche une page
     * @return Response
     * @throws NotFoundHttpException
     */
    public function showAction(Request $request) {

//        dump($request->query);
//        exit;

        $retour = $this->getSnapshot($request);

        if ($retour) {
            return $retour;
        }

        return $this->renderPage();
    }

    /**
     * Affiche une page sans le layout
     * @return Response
     * @throws NotFoundHttpException
     */
    public function showWithoutLayoutAction(Request $request) {

        $this->getSnapshot($request);

        $parameters = array(
            'page' => $this->snapshot,
        );

        return new Response(
                $this->get('templating')->render($this->getTemplate('show_without_layout'), $parameters)
        );
    }

    /**
     * Affiche le breadcrumb d'une page
     * @return Response
     * @throws NotFoundHttpException
     */
    public function showBreadcrumbAction(Request $request) {
        $route = preg_replace('#\.[a-z]{2}$#', '', $request->get('_route'));
        $this->snapshot = $this->getCmsSnapshotManager()->getSnapshotByRouteName($route);

        $parameters = array(
            'page' => $this->snapshot,
        );

        return new Response(
                $this->get('templating')->render($this->getTemplate('show_breadcrumb'), $parameters)
        );
    }


    // -------------------------------------------------------------------------
    // Helper methods
    // -------------------------------------------------------------------------

    /**
     * 
     */
    public function renderPage($template = null, $data = array()) {

        $parameters = array_merge(array(
            'page' => $this->snapshot,
            'base_layout' => $this->container->getParameter('base_layout')
                ), $data);

        if (!empty($this->redirect)) {
            if (is_array($this->redirect)) {
                return $this->redirectToRoute($this->redirect['route'], $this->redirect['params']);
            } else {
                return $this->redirectToRoute($this->redirect);
            }
        }
        return new Response(
                $this->get('templating')->render($this->getTemplate($template), $parameters)
        );
    }

    /**
     * recuperation du snapshot (contenu de la page)
     */
    public function getSnapshot(Request $request) {
        $route = preg_replace('#\.[a-z]{2}$#', '', $request->get('_route'));
        $this->snapshot = $this->getCmsSnapshotManager()->getSnapshotByRouteName($route);

        if ($this->snapshot === null) {
            throw new NotFoundHttpException();
        } else if (!empty($this->snapshot->getConnexion())) {
            if (!$this->get('security.authorization_checker')->isGranted($this->snapshot->getConnexion())) {
                $this->get('session')->set('connection_redirect', $request->getUri());
                if($this->snapshot->getRedirectSession()) {
                    $this->get('session')->set('connection_redirect_route', $this->snapshot->getRouteName());
                }
                return $this->redirectToRoute($this->snapshot->getRedirectConnexion()->getRouteName());
            }
        } else if (!empty($this->snapshot->getRedirect())) {
            return $this->redirectToRoute($this->snapshot->getRedirect()->getRouteName());
        }
        
        if($this->get('session')->get('connection_redirect_route', false) && $this->getUser() instanceof \Symfony\Component\Security\Core\User\UserInterface && !preg_match('/_fragment/',$request->getRequestUri())) {
            $this->get('session')->remove('connection_redirect_route');
        }

        $this->blockRepository = $this->getDoctrine()->getRepository('KreatysCmsBundle:Block');

        //dump($this->snapshot);
        $content = $this->snapshot->getContent();

        $new_content = '';
        foreach ($content as $block) {
            $new_content .= $this->processBlock($block);
        }

        $this->snapshot->setContentHtml($new_content);

        return false;
    }

    private function processBlock($block) {
        if ($block['processRequest']) {
            $service = $this->getBlockServiceManager()->getService($block['type']);
            $entity = $this->blockRepository->find($block['id']);
            if($entity !== null) {
                $service->callInit(true);
                $retourRequest = $service->processRequest($this->getRequest(), $entity);
                if (!empty($retourRequest)) {
                    $this->redirect = $retourRequest;
                }
                $block['content'] = $service->render($entity, true);
            } else {
                $block['content'] = '';
            }
        }

        $content = $block['content'];

        if (isset($block['children'])) {
            if (preg_match('#\[øchildrenø\]#', $content)) {
                $children_content = '';
                foreach ($block['children'] as $child) {
                    $children_content .= $this->processBlock($child);
                }
                $content = str_replace('[øchildrenø]', $children_content, $content);
            } else if (preg_match_all('#\[øchild(\d)ø\]#', $content, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $content = str_replace($match[0], $this->processBlock($block['children'][$match[1]]), $content);
                }
            }
        }

        return $content;
    }

    /**
     * Template pour afficher une page
     * @return string
     */
    private function getTemplate($template = null) {
        if (empty($template)) {
            $template = 'show_layout';
        }
        return $this->container->getParameter($template);
    }

    /**
     * @return BlockServiceManager
     */
    private function getBlockServiceManager() {
        return $this->container->get('kreatys_cms.manager.block');
    }

    /**
     * @return CmsPageManager
     */
    private function getCmsPageManager() {
        return $this->container->get('kreatys_cms.manager.cms_page');
    }

    /**
     * @return CmsSnapshotManager
     */
    private function getCmsSnapshotManager() {
        return $this->container->get('kreatys_cms.manager.cms_snapshot');
    }

}
