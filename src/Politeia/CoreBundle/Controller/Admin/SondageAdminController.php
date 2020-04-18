<?php

namespace Politeia\CoreBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController as Controller;

class SondageAdminController extends Controller
{
    public function resultatAction()
    {
        $object = $this->admin->getSubject();
       
        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $this->getIdParameter()));
        }
        
        $this->admin->setSubject($object);
        
        return $this->render('PoliteiaCoreBundle:Admin:Sondage/resultat.html.twig', array(
            'action' => 'resultat',            
            'object' => $object            
        ));
    }
}
