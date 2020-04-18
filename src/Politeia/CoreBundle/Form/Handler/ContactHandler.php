<?php

namespace Politeia\CoreBundle\Form\Handler;

use Kreatys\CmsBundle\Entity\Block;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContactHandler {

    public function init(array &$viewParameters) {
        
    }

    public function process(FormInterface $form, ContainerInterface $container, Block $block, array &$viewParameters) {
        if ($form->isValid()) {

            $data = $form->getData();
            
            $body = $container->get('templating')->render('email/contact.txt.twig', array('data' => $data));

            $message = \Swift_Message::newInstance()
                    ->setSubject('Nouveau contact via Politeia.fr')
                    ->setFrom(array($container->getParameter('mailer_from_address') => $container->getParameter('mailer_from_name')))
                    ->setTo($block->getSetting('email_contact'))
                    ->setReplyTo(array($data['email'] => $data['nom']))
                    ->setBody($body, 'text/plain')
            ;
            $container->get('mailer')->send($message);
            
            $session = $container->get('session');
            $session->getFlashBag()->add('success', 'Merci de votre message. Votre demande a bien été envoyée. Elle sera traitée dans les meilleurs délais.');

            return true;
        }

        return false;
    }

}
