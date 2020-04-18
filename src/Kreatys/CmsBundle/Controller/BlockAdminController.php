<?php

namespace Kreatys\CmsBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Kreatys\CmsBundle\Entity\Block;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class BlockAdminController extends CRUDController {

    protected $baseRoutePattern = 'blocks';

    public function createAjaxAction($page_id, $block_id) {
        $this->admin->newBlockPageId = $page_id;
        $this->admin->newBlockParentId = $block_id;
        return $this->createAction();
    }

    public function editAjaxAction($id = null) {
        return $this->editAction($id);
    }
    
    public function editContentsAjaxAction($id) {
        $this->admin->editContext = \Kreatys\CmsBundle\Admin\BlockAdmin::EDIT_CONTEXT_CONTENTS;
        return $this->editAction($id);
    }

    public function updateAjaxAction($id) {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('EDIT', $object)) {
            throw new AccessDeniedException();
        }

        if ($this->getRestMethod() == 'POST') {
            try {
                $field = $this->get('request')->get('field');
                $value = $this->get('request')->get('value');

                $contents = $object->getContents();
                $contents[$field] = $value;
                $object->setContents($contents);

                $this->admin->update($object);

                return $this->renderJson(array('result' => 'ok'));
            } catch (ModelManagerException $e) {
                $this->logModelManagerException($e);

                return $this->renderJson(array('result' => 'error'));
            }
        }
    }
    
    public function deleteAjaxAction($id) {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('DELETE', $object)) {
            throw new AccessDeniedException();
        }

        if ($this->getRestMethod() == 'DELETE') {
            try {
                $this->admin->delete($object);

                return $this->renderJson(array('result' => 'ok'));
            } catch (ModelManagerException $e) {
                $this->logModelManagerException($e);

                return $this->renderJson(array('result' => 'error'));
            }
        }
    }

    public function enabledAction(Block $block) {
        $em = $this->getDoctrine()->getEntityManager();

        $block->switchEnabled();
        $block->getPage()->setEdited(true);

        $em->persist($block);
        $em->flush();

        return $this->renderJson(array('enabled' => $block->getEnabled()));
    }

    /**
     * 
     * @param Block $block
     */
    public function reorderAction(Block $block, $siblingId, $pos) {
        
        $em = $this->getDoctrine()
                ->getManager()
        ;
        $repo = $em->getRepository('KreatysCmsBundle:Block');
        
        $siblingBlock = $repo->findById($siblingId);
        
        if($pos === 'prev') {
            $repo->persistAsPrevSiblingOf($block, $siblingBlock[0]);
        } else {
            $repo->persistAsNextSiblingOf($block, $siblingBlock[0]);
        }

        $em->flush();

        $response = true;

        return new JsonResponse(array(
            'response' => $response
        ));
    }

    /**
     * 
     */
    public function renderIconsAction() {
        $icons = array();

        $content = file_get_contents(dirname(__FILE__) . '/../Resources/public/assets/font-awesome/css/font-awesome.css');
        preg_match_all('/^\.fa-([a-z0-9-]+):before \{/m', $content, $icons);

        sort($icons[1]);

        return $this->render('KreatysCmsBundle:Icons:renderIcons.html.twig', array(
                    'icons' => $icons[1]
        ));
    }

}
