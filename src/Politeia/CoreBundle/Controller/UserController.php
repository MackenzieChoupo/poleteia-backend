<?php

namespace Politeia\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Politeia\CoreBundle\Manager\UserManager;

class UserController extends Controller
{
    /**
     * @var UserManager 
     */
    private  $userManager;
    
    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null)
    {
        parent::setContainer($container);
        
        $this->userManager = $container->get('politeia_core.manager.user');
                
    }

    
    /**
     * @Route("registration/confirm/{token}", name="user_registration_confirm")
     * @Template()
     */
    public function registrationConfirmAction($token)
    {
        $citoyen = $this->userManager->findUserByToken($token);
        if($citoyen !== null) {
            $citoyen->setEnabled(true);
            $citoyen->setToken(null);
            $this->userManager->updateUser($citoyen);
        }       
        
        return array(
            'citoyen' => $citoyen
        );
    }
    
    /**
     * @Route("password/reset/sucess", name="user_password_reset_success")
     * @Template()
     */
    public function passwordResetSucessAction()
    {       
        
        return array(
        );
    }    
    
    /**
     * @Route("password/reset/{token}", name="user_password_reset")
     * @Template()
     */
    public function passwordResetAction(Request $request, $token)
    {
        $citoyen = $this->userManager->findUserByToken($token);
             
        $form = $this->createForm('user_resetting');
        if($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if($form->isValid()) {                
                $citoyen->setPlainPassword($form->get('new')->getData());
                $citoyen->setToken(null);
                $citoyen->setPasswordRequestedAt(null);
                $this->userManager->updateUser($citoyen);                
                //$this->addFlash('success', 'Votre mot de passe a été changé  avec succès');
                return $this->redirectToRoute('user_password_reset_success');
            }            
        }
        
        return array(
            'token' => $token,
            'form' => $form->createView(),
            'citoyen' => $citoyen
        );
    }
}
