<?php

namespace Kreatys\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @Route("admin/contact", name="admin_contact")
     * @Template()
     */
    public function contactAction(Request $request)
    {
        
        $form = $this->createForm(new \Kreatys\AdminBundle\Form\Type\ContactType());
        $form->handleRequest($request);
        if($request->getMethod() === 'POST' && $form->isValid()) { 
            $sujet = $form->get('sujet')->getData();
            $message = $form->get('message')->getData();
            $user = $this->getUser();
            
            $mail = \Swift_Message::newInstance()
                ->setSubject('Formulaire de contact - Politeia')
                ->setFrom($user->getEmail(), $user->getProfil()->getNom().' '.$user->getProfil()->getPrenom())
                ->setTo($this->getParameter('email_dest_admin_contact'))
                ->setBody($this->renderView('KreatysAdminBundle:Mail:contact.html.twig', array('user' => $user, 'sujet' => $sujet, 'message' => $message)), 'text/html')
            ;
            $this->get('mailer')->send($mail);   

            $this->addFlash('sonata_flash_success', 'Votre message a bien été envoyé.');
            
            $form = $this->createForm(new \Kreatys\AdminBundle\Form\Type\ContactType());
        }
        
        return array(
            'form' => $form->createView(),
            'admin' => null,
            'base_template' => 'KreatysAdminBundle::standard_layout.html.twig',
            'admin_pool' => $this->get('sonata.admin.pool')
        );
    }
}


