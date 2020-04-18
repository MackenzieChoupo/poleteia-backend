<?php

namespace Kreatys\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Description of ListIconFieldsType
 *
 * @author remi
 */
class ListIconFieldsType extends AbstractType {

    protected $data;

    public function getName() {
        return 'listIconFields';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('icon', 'choice', array(
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'Icon'
                    ),
                    'choices' => $this->getIcons(),
                    'attr' => array(
                        'class' => 'select-fa-icon'
                    )
                ))
                ->add('text', 'textarea', array(
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'Texte',
                        'rows' => '4'
                    )
                ))
                ->add('url', 'text', array(
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'Url/Adresse'
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

        if (!empty($data)) {
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

    private function getIcons() {
        $icons = array();

        $content = file_get_contents(__DIR__ . '/../../../../web/bundles/kreatyscms/assets/font-awesome/css/font-awesome.css');
        preg_match_all('/^\.fa-([a-z0-9-]+):before \{/m', $content, $icons);
        
        sort($icons[1]);
        
        $data = array();
        foreach($icons[1] as $icon) {
            $data[$icon] = $icon;
        }
        
        return $data;
    }

}
