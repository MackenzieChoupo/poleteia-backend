<?php

namespace Kreatys\CmsBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Kreatys\CmsBundle\Entity\Parametre;
use Kreatys\CmsBundle\Entity\Seo;

/**
 * Description of ParametreAdminController
 *
 * @author remi
 */
class ParametreAdminController extends Controller {

    public function showAction($id = null) {
        // test si exist sinon on cree
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('KreatysCmsBundle:Parametre');

        if (!$repo->find($id)) {
            $parametre = new Parametre();
            $parametre->setThemeColor('default');
            $seo = new Seo();
            $parametre->setSeo($seo);
            $seo->setParametre($parametre);
            
            $em->persist($parametre);
            $em->flush();
        }
        
        return parent::showAction($id);
    }

}
