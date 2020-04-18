<?php

namespace Politeia\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageFileType  extends AbstractType
{
    protected $dir;
    
    public function __construct($dir)
    {
        $this->dir = $dir;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
    }

    public function buildView(FormView $view,FormInterface $form, array $options)
    {
        $view->vars['widget_type'] = 'image_file_widget';
        $view->vars['required'] = false; 
        $view->vars['dir'] = $this->dir; 
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {        
        $resolver->setDefaults(array(              
        ));        
    }
 
    public function getName()
    {
        return 'image_file';
    }
}
