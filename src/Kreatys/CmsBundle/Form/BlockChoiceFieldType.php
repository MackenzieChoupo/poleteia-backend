<?php

namespace Kreatys\CmsBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of BlockChoiceFieldType
 *
 * @author remi
 */
class BlockChoiceFieldType extends ChoiceType {

    public function getName() {
        return 'block_choice_type';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        parent::setDefaultOptions($resolver);
    }
}
