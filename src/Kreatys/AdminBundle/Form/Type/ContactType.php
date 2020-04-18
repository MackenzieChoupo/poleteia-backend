<?php

namespace Kreatys\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sujet', 'text', array(
                'label' => 'Sujet :',
                'required' => true,
                'constraints' => array(
                    new Assert\NotBlank()
                )
            ))
            ->add('message', 'textarea', array(
                'label' => 'Message :',
                'required' => true,
                'attr' => array(
                    'rows' => 8
                ),
                'constraints' => array(
                    new Assert\NotBlank()
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {        
        $resolver->setDefaults(array(              
        ));        
    }
 
    public function getName()
    {
        return 'admin_contact';
    }
}
