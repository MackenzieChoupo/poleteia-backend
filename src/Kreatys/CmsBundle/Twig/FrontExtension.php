<?php

namespace Kreatys\CmsBundle\Twig;

//use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;


class FrontExtension extends \Twig_Extension {

    /**
     * @var \Doctrine\ORM\EntityManager 
     */
    protected $em;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function getGlobals() {
        $params = $this->em->getRepository('KreatysCmsBundle:Parametre')->find(1);
        return array(
            'params' => $params
        );
    }

    /**
     * @return string
     */
    public function getName() {
        return 'front_extension';
    }

}
