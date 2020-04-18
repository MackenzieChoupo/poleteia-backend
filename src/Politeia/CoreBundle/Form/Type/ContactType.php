<?php

namespace Politeia\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;

class ContactType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nom', 'text', array(
                    'label' => false,
                    'required' => true,
                    'constraints' => array(
                        new Assert\NotBlank()
                    ),
                    'attr' => array(
                        'placeholder' => "VOTRE NOM"
                    )
                ))
                ->add('email', 'email', array(
                    'label' => false,
                    'required' => true,
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Email()
                    ),
                    'attr' => array(
                        'placeholder' => "VOTRE EMAIL"
                    )
                ))
                ->add('sujet', 'text', array(
                    'label' => false,
                    'required' => true,
                    'attr' => array(
                        'placeholder' => "SUJET"
                    )
                ))
                ->add('message', 'textarea', array(
                    'label' => false,
                    'attr' => array(
                        'rows' => 5,
                        'placeholder' => "VOTRE MESSAGE"
                    ),
                    'required' => true,
                    'constraints' => array(
                        new Assert\NotBlank()
                    )
                ))
//                ->add('recaptcha', 'ewz_recaptcha', array(
//                    'attr' => array(
//                        'options' => array(
//                            'theme' => 'light',
//                            'type' => 'image'
//                        )
//                    ),
//                    'mapped' => false,
//                    'constraints' => array(
//                        new RecaptchaTrue()
//                    ),
//                ))
        ;
    }

    public function getName() {
        return 'politeia_contact_form';
    }

}
