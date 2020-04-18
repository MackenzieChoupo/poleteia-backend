<?php

namespace Politeia\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReadOnlyEtatType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAttribute('choices', $options['choices']);
    }

    public function buildView(FormView $view,FormInterface $form, array $options)
    {
        $view->vars['widget_type'] = 'read_only_etat_widget';
        $view->vars['choices'] = $options['choices'];
        $view->vars['required'] = false;        
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array()
        ));
    }
    
    public function getName()
    {
        return 'read_only_etat';
    }
}
