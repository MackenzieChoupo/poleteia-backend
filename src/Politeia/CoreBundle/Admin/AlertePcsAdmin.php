<?php

namespace Politeia\CoreBundle\Admin;

use Politeia\CoreBundle\Entity\AlertePcs;
use Politeia\CoreBundle\Repository\CitoyenRepository;
use Politeia\CoreBundle\Repository\MairieRepository;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AlertePcsAdmin extends Admin
{
    protected $baseRoutePattern = 'alerte-pcs';
    
    /**
     * @var CitoyenRepository
     */
    private $citoyenRepository;
    
    private $container;
    
    public function __construct(
        $code,
        $class,
        $baseControllerName,
        CitoyenRepository $citoyenRepository,
        Container $container
    ) {
        parent::__construct($code, $class, $baseControllerName);
        $this->citoyenRepository = $citoyenRepository;
        $this->container = $container;
    }
    
    public function getObject($id) {
        if ($id === 'current') {
            $user = $this->getUser();
            $id = $user->getProfil()->getMairie()->getAlertePcs()->getId();
        }
        
        return parent::getObject($id);
    }
    
    protected function configureFormFields(FormMapper $form) {
        $form//->tab('Alerte')
            ->with(
                'Informations',
                array(
                    'class'     => 'col-md-6 col-sm-6 frm-texte',
                    'box_class' => 'box box-solid box-danger',
                )
            )->add(
                'titre',
                'text',
                array(
                    'label'       => 'Titre',
                    'required'    => true,
                    'constraints' => array(
                        new Assert\NotBlank(),
                    ),
                )
            )->add(
                'texte',
                'textarea',
                array(
                    'label'       => 'Texte',
                    'required'    => false,
                    'attr'        => array(
                        'class' => 'texte-simple-wysiwyg',
                        'rows'  => 10,
                    ),
                    'constraints' => array(
                        new Assert\NotBlank(),
                    ),
                )
            )->end()->with(
                'Publication',
                array(
                    'class'     => 'col-md-6 col-sm-6 frm-texte',
                    'box_class' => 'box box-solid box-danger',
                )
            )->add(
                'online',
                null,
                array(
                    'label'    => 'En ligne',
                    'required' => false,
                )
            )->end()//->end()
        ;
    }
    
    protected function configureRoutes(RouteCollection $collection) {
        $collection->clearExcept(array('edit'));
    }
    
    public function preUpdate($object) {
        $targetUsers = $this->citoyenRepository->subscribedToMairie($this->getUser()->getProfil()->getMairie()->getId());
        $pushSender = $this->container->get('app.push.sender');
        $pushSender->sendPush($object->getMairie()->getVille(), $object->getTitre(), $targetUsers);
    }
    
    /**
     *
     * @return \Kreatys\UserBundle\Entity\User
     */
    private function getUser() {
        return $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
    }
}
