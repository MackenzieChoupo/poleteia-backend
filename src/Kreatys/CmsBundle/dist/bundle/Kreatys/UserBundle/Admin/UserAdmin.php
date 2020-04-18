<?php

namespace Kreatys\UserBundle\Admin;

use Sonata\UserBundle\Admin\Model\UserAdmin as BaseAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Validator\Constraints as Assert;

class UserAdmin extends BaseAdmin {

    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'users';
    protected $translationDomain = 'messages';

    protected function configureListFields(ListMapper $listMapper) {
        parent::configureListFields($listMapper);
        $listMapper
                ->remove('createdAt')
                ->remove('groups')
                ->remove('impersonating')
                ->add('last_login', 'datetime', array('format' => 'd/m/Y H:i'))
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->with('General')
                ->add('username')
                ->add('email')
                ->end()
                ->with('Profile')
                ->add('profil.nom', null, array(
                    'label' => 'Nom'
                ))
                ->add('profil.prenom', null, array(
                    'label' => 'Prénom'
                ))
                ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filterMapper) {
        parent::configureDatagridFilters($filterMapper);

        $filterMapper->remove('id');
        $filterMapper->remove('groups');
    }

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->tab('user', array(
                    'translation_domain' => 'messages'
                ))
                ->with('informations_connexion', array(
                    'class' => 'col-md-6',
                    'box_class' => 'box box-solid box-danger',
                    'translation_domain' => 'messages'
                ))
                ->add('username')
                ->add('email')
                ->add('plainPassword', 'text', array(
                    'required' => (!$this->getSubject() || is_null($this->getSubject()->getId()))
                ))
                ->add('locked', null, array('required' => false))
                ->add('expired', null, array('required' => false))
                ->add('enabled', null, array('required' => false))
                ->add('credentialsExpired', null, array('required' => false))
                ->end()
                ->end()
                ->tab('profil', array(
                    'translation_domain' => 'messages'
                ))
                ->with('informations_profil', array(
                    'class' => 'col-md-6',
                    'box_class' => 'box box-solid box-danger',
                    'translation_domain' => 'messages'
                ))
                ->add('profil.nom', null, array(
                    'label' => 'profil.nom'
                ))
                ->add('profil.prenom', null, array(
                    'label' => 'profil.prenom'
                ))
                ->end()
                ->end()
        ;

        if ($this->getSubject() && !$this->getSubject()->hasRole('ROLE_SUPER_ADMIN')) {
            $formMapper
                    ->tab('roles_droits', array(
                        'translation_domain' => 'messages'
                    ))
                    ->with('user_management', array(
                        'class' => 'col-md-6',
                        'box_class' => 'box box-solid box-danger',
                        'translation_domain' => 'messages'
                    ))
                    ->add('role', 'choice', array(
                        'label' => 'user.role',
                        'expanded' => true,
                        'required' => false,
                        'choices' => array(
                            'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
                            'ROLE_ADMIN' => 'ROLE_ADMIN',
                            '' => 'ROLE_USER_'
                        ),
                        'multiple' => false,
                        'empty_value' => false
                    ))
                    ->end()
                    ->end()
            ;
        }
    }

}
