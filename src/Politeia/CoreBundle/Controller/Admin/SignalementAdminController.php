<?php

namespace Politeia\CoreBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SignalementAdminController extends Controller
{
    public function deletePhotoAction() {
        $object = $this->admin->getSubject();
        
        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $this->getIdParameter()));
        }
        
        $fs = new Filesystem();
        $fs->remove(__DIR__ . '/../../../../../web/uploads/signalement/' . $object->getPhotoName());
        
        $object->setPhotoName(null);
        
        $this->admin->setSubject($object);
        $object = $this->admin->update($object);
        
        return new RedirectResponse($this->admin->generateObjectUrl('edit', $object));
    }
    
    public function moderatedAction() {
        return $this->listAction();
    }
}
