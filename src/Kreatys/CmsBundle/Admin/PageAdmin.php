<?php

namespace Kreatys\CmsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Kreatys\CmsBundle\Manager\CmsPageManager;
use Symfony\Component\Security\Core\SecurityContext;
use Kreatys\CmsBundle\Form\TwoFieldsType;
use Symfony\Component\Form\CallbackTransformer;

/**
 * Description of PageAdmin
 *
 * @author remi
 */
class PageAdmin extends Admin {

    protected $baseRoutePattern = 'pages';
    protected $pageManager;
    protected $securityContext;

    public function createQuery($context = 'list') {
        $query = parent::createQuery($context);

        if ($context === 'list') {
            $query->orderBy('o.root', 'ASC');
            $query->addOrderBy('o.lft', 'ASC');
//            $query->andWhere(
//                    $query->expr()->eq($query->getRootAliases()[0] . '.root', ':root')
//            );
//            $query->setParameter('root', 1);
        }

        return $query;
    }

    protected function configureFormFields(FormMapper $form) {
        $subject = $this->getSubject();
        $id = $subject->getId();
        $pageOptions = $this->getPageOptions();

//        parent::configureFormFields($form);
        $form
                ->tab('Page')
                // ***** Configuration de la page
                ->with('Configuration', array(
                    'class' => 'col-md-5',
                    'box_class' => 'box box-solid box-danger'
                ))
        ;
        $form
                ->add('url', 'text', array(
                    'label' => 'Url de la page',
                    'attr' => array(
                        'disabled' => 'disabled'
                    ),
                    'required' => false
                ))
                ->add('name', 'text', array(
                    'label' => 'Nom de la page'
                ))
        ;
        if ($pageOptions['titre']['input']) {
            $form
                    ->add('title', 'text', array(
                        'label' => 'Titre de la page',
                        'required' => false
                    ))
            ;
        }
        $form
                ->add('parent', 'entity', array(
                    'class' => 'KreatysCmsBundle:Page',
                    'label' => 'Parent',
                    'required' => false,
                    'query_builder' => function($er) use ($id) {
                        $qb = $er->createQueryBuilder('p');
                        if ($id) {
                            $qb
                            ->where('p.id <> :id')
                            ->setParameter('id', $id);
                        }
                        $qb
                        ->orderBy('p.lft, p.name', 'ASC');
                        return $qb;
                    },
                    'group_by' => function($val, $key, $index) {
                        if (!empty($val)) {
                            if (!empty($val->getParent())) {
                                return $val->getParent();
                            }
                        }
                    },
                    'preferred_choices' => function ($val, $key) {
                        return empty($val->getParent());
                    }
                ))
                ->add('redirect', 'entity', array(
                    'class' => 'KreatysCmsBundle:Page',
                    'label' => 'Redirection vers',
                    'required' => false,
                    'query_builder' => function($er) use ($id) {
                        $qb = $er->createQueryBuilder('p');
                        if ($id) {
                            $qb
                            ->where('p.id <> :id')
                            ->setParameter('id', $id);
                        }
                        $qb
                        ->orderBy('p.lft, p.name', 'ASC');
                        return $qb;
                    },
                    'group_by' => function($val, $key, $index) {
                        if (!empty($val)) {
                            if (!empty($val->getParent())) {
                                return $val->getParent();
                            }
                        }
                    },
                    'preferred_choices' => function ($val, $key) {
                        return empty($val->getParent());
                    }
                ))
            ;
        if ($pageOptions['fil_ariane']['lien']) {
        $form
                ->add('breadcrumb_link', 'checkbox', array(
                    'label' => 'Lien sur fil d\'ariane ?',
                    'attr' => array(
                        'class' => 'icheck_blue'
                    ),
                    'required' => false
                ))
            ;
        }
        if ($pageOptions['titre']['afficher']) {
        $form
                ->add('viewTitle', 'checkbox', array(
                    'label' => 'Afficher le titre ?',
                    'attr' => array(
                        'class' => 'icheck_blue'
                    ),
                    'required' => false
                ))
            ;
        }
        if ($pageOptions['fil_ariane']['afficher']) {
        $form
                ->add('breadcrumb', 'checkbox', array(
                    'label' => 'Afficher le fil d\'ariane ?',
                    'attr' => array(
                        'class' => 'icheck_blue'
                    ),
                    'required' => false
                ))
            ;
        }
        $form
                ->add('enabled', 'checkbox', array(
                    'label' => 'Mettre en ligne ?',
                    'attr' => array(
                        'class' => 'icheck_blue'
                    ),
                    'required' => false
                ))
                ->end()
                // ***** Referencement (SEO)
                ->with('Référencement (SEO)', array(
                    'class' => 'col-md-7',
                    'box_class' => 'box box-solid box-danger'
                ))
                ->add('custom_url', 'text', array(
                    'label' => 'Url personnalisée',
                    'required' => false
                ))
                ->add('meta_title', 'text', array(
                    'label' => 'Meta titre',
                    'required' => false
                ))
                ->add('meta_keywords', 'textarea', array(
                    'label' => 'Meta keywords',
                    'required' => false
                ))
                ->add('meta_description', 'textarea', array(
                    'label' => 'Meta description',
                    'required' => false,
                    'attr' => array(
                        'rows' => '8'
                    )
                ))
                ->end()
                ->end()
        ;
        $form
                ->tab('Paramètres avancés')
                ->with('Google', array(
                    'class' => 'col-md-6',
                    'box_class' => 'box box-solid box-danger'
                ))
                ->add('google_analitics', 'textarea', array(
                    'label' => 'Analitics',
                    'required' => false,
                    'attr' => array(
                        'rows' => '11'
                    )
                ))
                ->end()
        ;
        if ($this->securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $form
                    ->with('Mise en page', array(
                        'class' => 'col-md-6',
                        'box_class' => 'box box-solid box-danger'
                    ))
                    ->add('stylesheets', 'textarea', array(
                        'label' => 'Stylesheets',
                        'required' => false,
                        'attr' => array(
                            'rows' => '4'
                        )
                    ))
                    ->add('javascripts', 'textarea', array(
                        'label' => 'Javascripts',
                        'required' => false,
                        'attr' => array(
                            'rows' => '4'
                        )
                    ))
                    ->end()
            ;
        }
        $form
                ->end()
        ;

        if ($this->securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $form
                    ->tab('SuperAdmin')
                    // ***** Configuration de la page
                    ->with('Paramètres SuperAvancés', array(
                        'class' => 'col-md-4 col-sm-12',
                        'box_class' => 'box box-solid box-danger'
                    ))
                    ->add('speciale', 'checkbox', array(
                        'label' => 'Page spéciale',
                        'attr' => array(
                            'class' => 'icheck_blue'
                        ),
                        'required' => false
                    ))
                    ->add('slug', 'text', array(
                        'label' => 'Slug',
                        'attr' => array(
                            'disabled' => 'disabled'
                        ),
                        'required' => false
                    ))
                    ->add('url_suffixe', 'text', array(
                        'label' => 'Url suffixe',
                        'required' => false
                    ))
                    ->add('route_name', 'text', array(
                        'label' => 'Route name',
                        'required' => false
                    ))
                    ->add('route_options', 'collection', array(
                        'label' => 'Route options',
                        'required' => false,
                        'type' => new TwoFieldsType(),
                        'options' => array(
                            'required' => false,
                            'label' => false
                        ),
                        'allow_add' => true,
                        'allow_delete' => true
                    ))
                    ->add('route_requirements', 'collection', array(
                        'label' => 'Route requirements',
                        'required' => false,
                        'type' => new TwoFieldsType(),
                        'options' => array(
                            'required' => false,
                            'label' => false
                        ),
                        'allow_add' => true,
                        'allow_delete' => true
                    ))
                    ->end()
                    ->with('Connexion', array(
                        'class' => 'col-md-4 col-sm-12',
                        'box_class' => 'box box-solid box-danger'
                    ))
                    ->add('connexion', 'choice', array(
                        'label' => 'Connexion obligatoire',
                        'choices' => $this->getConfigurationPool()->getContainer()->getParameter('page_security'),
                        'empty_value' => 'non',
                        'required' => false
                    ))
                    ->add('redirect_connexion', 'entity', array(
                        'class' => 'KreatysCmsBundle:Page',
                        'label' => 'Redirection vers (si connexion obligatoire et non connecté)',
                        'required' => false,
                        'query_builder' => function($er) use ($id) {
                            $qb = $er->createQueryBuilder('p');
                            if ($id) {
                                $qb
                                ->where('p.id <> :id')
                                ->setParameter('id', $id);
                            }
                            $qb
                            ->orderBy('p.lft, p.name', 'ASC');
                            return $qb;
                        },
                        'group_by' => function($val, $key, $index) {
                            if (!empty($val)) {
                                if (!empty($val->getParent())) {
                                    return $val->getParent();
                                }
                            }
                        },
                        'preferred_choices' => function ($val, $key) {
                            return empty($val->getParent());
                        }
                    ))
                    ->add('redirect_session', 'checkbox', array(
                        'label' => 'Mettre en session la route ?',
                        'attr' => array(
                            'class' => 'icheck_blue'
                        ),
                        'required' => false
                    ))
                    ->end()
                    ->end()
            ;
        }
    }

    protected function configureDatagridFilters(DatagridMapper $filter) {
//        parent::configureDatagridFilters($filter);
        $filter->add('name', null, array(
                    'label' => 'Nom'
                ))
                ->add('title', null, array(
                    'label' => 'Titre'
                ))
                ->add('enabled', 'doctrine_orm_boolean', array(
                    'label' => 'Validated ?',
                    'operator_type' => 'hidden',
                    'advanced_filter' => false,
                        ), 'choice', array(
                    'choices' => array(
                        2 => 'Non',
                        1 => 'Oui'
                    )
                ))
                ->add('edited', 'doctrine_orm_boolean', array(
                    'label' => 'Validated ?',
                    'operator_type' => 'hidden',
                    'advanced_filter' => false,
                        ), 'choice', array(
                    'choices' => array(
                        2 => 'Non',
                        1 => 'Oui'
                    )
                ))
        ;
    }

    protected function configureListFields(ListMapper $list) {
//        parent::configureListFields($list);
        $list
                ->addIdentifier('name', null, array(
                    'label' => 'Nom',
//                    'route' => array('name' => 'preview')
                ))
                ->add('title', null, array(
                    'label' => 'Titre'
                ))
                ->add('edited', null, array(
                    'label' => 'Editée',
                    'template' => 'KreatysCmsBundle:Admin:edited.html.twig'
                ))
                ->add('enabled', null, array(
                    'label' => 'En ligne',
                    'editable' => true
                ))
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'compose' => array(
                            'template' => 'KreatysCmsBundle:Admin:CRUD/list__action_compose.html.twig'
                        ),
                        'preview' => array(
                            'template' => 'KreatysCmsBundle:Admin:CRUD/list__action_preview.html.twig'
                        ),
                        'edit' => array(),
                        'delete' => array()
                    )
                ))
        ;
    }

    public function prePersist($object) {
        $object->setEdited(true);
        if(empty($object->getRouteName())) {
            $object->setRouteName(sha1('kreatys_cms_'));
        }

        $this->pageManager->fixUrl($object);

        parent::prePersist($object);
    }

    public function postPersist($object) {
        $this->pageManager->addMasterblock($object);
        $object->setRouteName('kreatys_cms_' . $object->getId());
        $this->getModelManager()->update($object);
    }

    public function preUpdate($object) {
//        dump($object);
//        exit;
        $object->setEdited(true);
        $this->pageManager->fixUrl($object);

        parent::preUpdate($object);
    }

    protected function configureRoutes(RouteCollection $collection) {
        parent::configureRoutes($collection);

        $collection->add('tree', 'tree');
        $collection->add('preview', $this->getRouterIdParameter() . '/preview');
        $collection->add('compose', $this->getRouterIdParameter() . '/compose');
        $collection->add('publish', $this->getRouterIdParameter() . '/publish');
        $collection->add('publish_force', $this->getRouterIdParameter() . '/publish/force');
        $collection->add('duplicate', 'duplicate');
        $collection->remove('show');
    }

    /**
     * @param CmsPageManager $pageManager
     */
    public function setPageManager(CmsPageManager $pageManager) {
        $this->pageManager = $pageManager;
    }

    public function setSecurityContext(SecurityContext $securityContext) {
        $this->securityContext = $securityContext;
    }

    private function getPageOptions() {
        return $this->getConfigurationPool()->getContainer()->getParameter('page_options');
    }

}
