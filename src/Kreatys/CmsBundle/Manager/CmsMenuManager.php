<?php
namespace Kreatys\CmsBundle\Manager;

use Doctrine\ORM\EntityManager;
use Kreatys\CmsBundle\Entity\Menu;

/**
 * Description of CmsMenuManager
 *
 * @author remi
 */
class CmsMenuManager {

    /**
     * @var \Doctrine\ORM\EntityManager 
     */
    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function setMenuRacine() {
        // verification si menu racine existe
        $menuracine = $this->getMenuRacine();
        if(!$menuracine) {
            // creation du menu racine
            $menu = new Menu();
            $menu->setLabel('Menu racine');
            $menu->setUrl('/');
            $menu->setEnabled(true);
            
            $this->em->persist($menu);
            $this->em->flush();
        }
    }
    
    public function getMenuRacine() {
        return $this->em->getRepository('KreatysCmsBundle:Menu')->findOneBy(array('parent' => null));
    }
    
}
