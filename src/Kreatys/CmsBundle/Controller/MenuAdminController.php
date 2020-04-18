<?php

namespace Kreatys\CmsBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Kreatys\CmsBundle\Entity\Menu;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Description of MenuAdminController
 *
 * @author remi
 */
class MenuAdminController extends Controller {

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
        $em = $this->getDoctrine()
                ->getManager()
        ;
        $repo = $em->getRepository('KreatysCmsBundle:Menu');
        if ($repo->verify() !== true) {
            $repo->recover();
            $em->flush();
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
        ));
    }

    public function enabledAction(Menu $menu) {
        $em = $this->getDoctrine()->getEntityManager();

        $menu->switchEnabled();

        $em->persist($menu);

        $em->flush();

        return $this->renderJson(array('enabled' => $menu->getEnabled()));
    }

    /**
     * 
     * @param Menu $menu
     */
    public function reorderAction(Menu $menu, $siblingId, $pos) {

        $em = $this->getDoctrine()
                ->getManager()
        ;
        $repo = $em->getRepository('KreatysCmsBundle:Menu');

        $siblingMenu = $repo->findById($siblingId);

        if ($pos === 'prev') {
            $repo->persistAsPrevSiblingOf($menu, $siblingMenu[0]);
        } else {
            $repo->persistAsNextSiblingOf($menu, $siblingMenu[0]);
        }

        $em->flush();

        $response = true;

        return new JsonResponse(array(
            'response' => $response
        ));
    }
    
}
