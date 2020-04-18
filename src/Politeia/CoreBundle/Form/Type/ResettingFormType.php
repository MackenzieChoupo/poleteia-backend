<?php

namespace Politeia\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ResettingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('new', 'repeated', array(
            'type' => 'password',
            'first_options' => array('label' => 'Nouveau mot de passe :'),
            'second_options' => array('label' => 'Vérification :'),
            'invalid_message' => 'Les deux mots de passe ne sont pas identiques',
            'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 8,
                        'minMessage' => 'Le mot de passe est trop court'
                    )),
                    new Assert\Regex(array(                        
                        //'pattern' => '#\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*#',
                        'pattern' => '#\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*#',
                        'message' => 'Le mot de passe doit contenir au moins une minuscule, une majuscule et un nombre'
                    ))
                )
        ));
    }

    public function getName()
    {
        return 'user_resetting';
    }
}
