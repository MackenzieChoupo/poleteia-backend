<?php

namespace Politeia\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Kreatys\UserBundle\Repository\UserRepository;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;

class MairieAdmin extends Admin
{
    protected $baseRoutePattern = 'mairies';
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserManagerInterface
     */
    private $userManager;
    /**
     * @var MailerInterface
     */
    private $userMailer;
    /**
     * @var TokenGeneratorInterface
     */
    private $userTokenGenerator;
    
    public function __construct(
        $code,
        $class,
        $baseControllerName,
        UserRepository $userRepository,
        UserManagerInterface $userManager,
        MailerInterface $userMailer,
        TokenGeneratorInterface $userTokenGenerator
    ) {
        parent::__construct($code, $class, $baseControllerName);
        $this->userRepository = $userRepository;
        $this->userManager = $userManager;
        $this->userMailer = $userMailer;
        $this->userTokenGenerator = $userTokenGenerator;
    }
    
    public function getObject($id) {
        if ($id === 'current') {
            $user = $this->getUser();
            $id = $user->getProfil()->getMairie()->getId();
        }
        
        return parent::getObject($id);
    }
    
    protected function configureDatagridFilters(DatagridMapper $filter) {
        $filter->add('ville')->add('codePostal');
    }
    
    protected function configureListFields(ListMapper $list) {
        $list->add(
            'ville',
            null,
            array(
                'label' => 'Ville',
            )
        )->add(
            'codePostal',
            null,
            array(
                'label' => 'CP',
            )
        )//                ->add('adresse', null, array(
//                    'label' => 'Adresse'
//                ))
//                ->add('site', null, array(
//                    'label' => 'Site internet'
//                ))
        ->add(
            'tel',
            null,
            array(
                'label' => 'Téléphone',
            )
        )->add(
            'email',
            null,
            array(
                'label' => 'Email',
            )
        )//                ->add('horaires', null, array(
//                    'label' => 'Horaires'
//                ))
        ->add(
            'nbCitoyen',
            null,
            array(
                'label' => 'Nb utilisateurs',
            )
        )->add(
            'updated',
            'date',
            array(
                'label'  => 'Dern. Modif.',
                'format' => 'd/m/Y',
            )
        )->add(
            'reportsEnabled',
            null,
            array(
                'label'    => 'Signalements activés.',
                'editable' => true,
                'required' => false,
            )
        )->add(
            '_action',
            'actions',
            array(
                'actions' => array(
                    'edit'   => array(),
                    'delete' => array(),
                ),
            )
        );
    }
    
    protected function configureFormFields(FormMapper $form) {
        $subject = $this->getSubject();
        if (!is_bool($subject)) {
            if ($subject->getProfil() === null) {
                $subject->setProfil(new \Kreatys\UserBundle\Entity\Profil());
            }
            
            $form->tab('Infos')->with(
                'Mairie',
                array(
                    'class'     => 'col-md-6 col-sm-6 frm-texte',
                    'box_class' => 'box box-solid box-danger',
                )
            );
            
            if ($this->getUser()->hasRole('ROLE_ADMIN')) {
                $form->add(
                    'ville',
                    null,
                    array(
                        'label'       => 'Ville',
                        'required'    => true,
                        'constraints' => array(
                            new Assert\NotBlank(),
                        ),
                    )
                )->add(
                    'codePostal',
                    null,
                    array(
                        'label'       => 'Code postal',
                        'required'    => true,
                        'constraints' => array(
                            new Assert\NotBlank(),
                        ),
                    )
                )->add(
                    'reportsEnabled',
                    null,
                    array(
                        'label'    => 'Signalements activés.',
                        'required' => false,
                    )
                );
            }
            
            $form->add(
                'adresse',
                null,
                array(
                    'label'       => 'Adresse',
                    'required'    => true,
                    'constraints' => array(
                        new Assert\NotBlank(),
                    ),
                )
            )->add(
                'site',
                null,
                array(
                    'label'       => 'Site internet',
                    'required'    => true,
                    'constraints' => array(
                        new Assert\NotBlank(),
                        //new Assert\Url()
                    ),
                    'help'        => 'Format : http://www.example.com/',
                )
            )->add(
                'email',
                null,
                array(
                    'label'       => 'Email',
                    'required'    => true,
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Email(),
                    ),
                )
            )->add(
                'tel',
                null,
                array(
                    'label'       => 'Téléphone',
                    'required'    => true,
                    'constraints' => array(
                        new Assert\NotBlank(),
                    ),
                )
            )->add(
                'telAnimation',
                null,
                array(
                    'label'       => 'Téléphone animation',
                    'required'    => false,
                    'constraints' => array(),
                )
            )->add(
                'telUrbanisme',
                null,
                array(
                    'label'       => 'Téléphone urbanisme',
                    'required'    => false,
                    'constraints' => array(),
                )
            )->add(
                'baseline',
                null,
                array(
                    'label'       => 'Texte introduction',
                    'required'    => false,
                    'constraints' => array(),
                )
            )->add(
                'infos',
                'textarea',
                array(
                    'label'    => 'Informations',
                    'required' => false,
                    'attr'     => array(
                        'class' => 'texte-simple-wysiwyg',
                        'rows'  => 10,
                    ),
                )
            )->add(
                'imageFile',
                'file',
                array(
                    'label'    => 'Image',
                    'required' => false,
                    'help'     => '',
                )
            )->add(
                'image',
                new \Politeia\CoreBundle\Form\Type\ImageFileType('mairie'),
                array(
                    'label'    => false,
                    'required' => false,
                    'mapped'   => false,
                    'data'     => (int)$subject->getId() > 0 ? $subject->getImageName() : '',
                ),
                array(
                    'type' => 'text',
                )
            )->end()->with(
                'Horaires',
                array(
                    'class'     => 'col-md-6 col-sm-6 frm-texte',
                    'box_class' => 'box box-solid box-danger',
                )
            )->add(
                'horaires',
                'sonata_type_collection',
                array(
                    'label'        => false,
                    'help'         => '',
                    //                    'by_reference' => false,
                    'type_options' => array(
                        'delete' => true,
                    ),
                ),
                array(
                    'edit'         => 'inline',
                    'inline'       => 'table',
                    'sortable'     => 'position',
                    'allow_delete' => true,
                    'allow_add'    => true,
                )
            )->end()->end();
            
            //if($this->getUser()->hasRole('ROLE_ADMIN')) {
            $form->tab('Adminstrateur')->with(
                'Compte utilisateur',
                array(
                    'class'     => 'col-md-4 col-sm-6',
                    'box_class' => 'box box-solid box-danger',
                )
            )->add(
                'profil.nom',
                null,
                array(
                    'label'       => 'Nom',
                    'required'    => true,
                    'constraints' => array(
                        new Assert\NotBlank(),
                    ),
                )
            )->add(
                'profil.prenom',
                null,
                array(
                    'label'       => 'Prénom',
                    'required'    => true,
                    'constraints' => array(
                        new Assert\NotBlank(),
                    ),
                )
            );
            if ($subject->isNew() || $subject->getProfil()->isNew()) {
                $form->add(
                    'userEmail',
                    'text',
                    array(
                        'label'       => 'Identifiant / Email',
                        'required'    => true,
                        'constraints' => array(
                            new Assert\NotBlank(),
                            new Assert\Email(),
                            new Assert\Callback(
                                [
                                    'callback' => function ($value, ExecutionContextInterface $context) {
                                        if ($value === '') {
                                            return;
                                        }
                                        if ($this->userRepository->emailExist($value)) {
                                            $context->addViolation('Cette email existe déjà.');
                                        }
                                    },
                                ]
                            ),
                        ),
                    )
                )->add(
                    'userPlainPassword',
                    'text',
                    array(
                        'label'       => 'Mot de passe',
                        'required'    => true,
                        'constraints' => array(
                            new Assert\NotBlank(),
                        ),
                    )
                );
            } else {
                $form->add(
                    'oldEmail',
                    'hidden',
                    array(
                        'label'    => false,
                        'required' => false,
                        'mapped'   => false,
                        'data'     => $subject->getProfil()->getUser()->getEmail(),
                    )
                )->add(
                    'profil.user.email',
                    'text',
                    array(
                        'label'       => 'Identifiant / Email',
                        'required'    => true,
                        'constraints' => array(
                            new Assert\NotBlank(),
                            new Assert\Email(),
                            new Assert\Callback(
                                [
                                    'callback' => function ($value, ExecutionContextInterface $context) {
                                        if ($value === '') {
                                            return;
                                        }
                                        if ($this->userRepository->emailExist(
                                            $value,
                                            (int)$this->getSubject()->getProfil()->getUser()->getId()
                                        )) {
                                            $context->addViolation('Cette email existe déjà.');
                                        }
                                    },
                                ]
                            ),
                        ),
                    )
                )->add(
                    'userPlainPassword',
                    'text',
                    array(
                        'label'    => 'Mot de passe',
                        'required' => false,
                        'help'     => 'Saisissez ce champ pour modifier le mot de passe',
                    )
                );
            }
            
            $form->end()->end();
            //}
        }
    }
    
    public function prePersist($object) {
        foreach ($object->getHoraires() as $horaire) {
            $horaire->setMairie($object);
        }
        /*$themes = ['Éducation & Jeunesse', 'Social', 'Urbanisme & travaux', 'Animation'];
        foreach($themes as $theme) {
            $baiTheme = new \Politeia\CoreBundle\Entity\BoiteAIdeeTheme();
            $baiTheme->setTitre($theme);
            $baiTheme->setOnline(true);
            $object->addBoiteAIdeeTheme($baiTheme);
        }*/
    }
    
    public function preUpdate($object) {
        foreach ($object->getHoraires() as $horaire) {
            $horaire->setMairie($object);
        }
    }
    
    public function postPersist($object) {
        $this->createUser($object);
    }
    
    public function postUpdate($object) {
        if ($object->getReportsEnabled()) {
            $user = $object->getProfil()->getUser();
            $user->addRole('ROLE_REPORTS_ENABLED');
            $this->userManager->updateUser($user);
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->getConfigurationPool()->getContainer()->get('security.context')->setToken($token);
        } else {
            $user = $object->getProfil()->getUser();
            $user->removeRole('ROLE_REPORTS_ENABLED');
            $this->userManager->updateUser($user);
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->getConfigurationPool()->getContainer()->get('security.context')->setToken($token);
        }
        
        if ($this->getUser()->hasRole('ROLE_ADMIN')) {
            if ($object->getProfil()->getUser() === null) {
                $this->createUser($object);
            } else {
                $user = $object->getProfil()->getUser();
                if ($object->getUserPlainPassword() != '') {
                    $user->setPlainPassword($object->getUserPlainPassword());
                    $this->userManager->updatePassword($user);
                    $this->userManager->updateUser($user);
                }
                if ($this->getForm()->has('oldEmail') && $this->getForm()->get('oldEmail') != $user->getEmail()) {
                    $user->setUsername($user->getEmail());
                    $this->userManager->updateUser($user);
                }
            }
        }
    }
    
    private function createUser(\Politeia\CoreBundle\Entity\Mairie $mairie) {
        $user = $this->userManager->createUser();
        $user->addRole('ROLE_MAIRIE');
        $user->setEmail($mairie->getUserEmail());
        $user->setUsername($mairie->getUserEmail());
        $user->setPlainPassword($mairie->getUserPlainPassword());
        $user->setProfil($mairie->getProfil());
        $user->setEnabled(true);
        $this->userManager->updatePassword($user);
        $this->userManager->updateUser($user);
        
        // Envoyer mail de confirm ??
        //$user->setConfirmationToken($this->originalTokenGenerator->generateToken());        
        //$this->userMailer->sendConfirmationEmailMessage($user);
    }
    
    /**
     *
     * @return \Kreatys\UserBundle\Entity\User
     */
    private function getUser() {
        $token = $this->getConfigurationPool()->getContainer()->get('security.context')->getToken();
        if ($token !== null) {
            return $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
        } else {
            return null;
        }
    }
}
