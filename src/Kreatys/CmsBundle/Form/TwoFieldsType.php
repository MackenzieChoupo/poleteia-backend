<?php

namespace Kreatys\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Description of 2FieldsType
 *
 * @author remi
 */
class TwoFieldsType extends AbstractType {
    
    protected $hasRoleSuperAdmin;
    
    function __construct($hasRoleSuperAdmin = true) {
        $this->hasRoleSuperAdmin = $hasRoleSuperAdmin;
    }

    public function getName() {
        return 'twoFields';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('key', 'text', array(
//                    'mapped' => false,
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'Clé'
                    ),
                    'read_only' => $this->hasRoleSuperAdmin ? false:true
                ))
                ->add('value', 'text', array(
//                    'mapped' => false,
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'Valeur'
                    )
                ))
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onFormEvent'));
        $builder->addEventListener(FormEvents::POST_SET_DATA, array($this, 'onFormEvent'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onFormEvent'));
        $builder->addEventListener(FormEvents::SUBMIT, array($this, 'onFormEvent'));
        $builder->addEventListener(FormEvents::POST_SUBMIT, array($this, 'onFormEvent'));
    }

    public function onFormEvent(FormEvent $event) {
        $data = $event->getData();
        //dump($event->getName());
        //dump($data);
//        exit;
//        if($event->getName() === '') {
//            
//        }
        
        if(!empty($data)) {
            $this->data = $data;
        } else {
            $event->setData(array());
        }
        
        if ($event->getName() === 'form.bind') {
            $event->setData($this->data);
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
//            'csrf_protection' => false,
//            'cascade' => true
        ));
    }

}
