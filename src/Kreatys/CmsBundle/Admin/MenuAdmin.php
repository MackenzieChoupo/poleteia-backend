<?php

namespace Kreatys\CmsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Kreatys\CmsBundle\Manager\CmsMenuManager;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Description of MenuAdmin
 *
 * @author remi
 */
class MenuAdmin extends Admin {

    protected $baseRoutePattern = 'menu';
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'lft'
    );
    protected $menuManager;
    protected $securityContext;

    public function createQuery($context = 'list') {
        if ($context == 'list') {
            $query = parent::createQuery($context);
            $query->andWhere(
                    $query->expr()->eq($query->getRootAliases()[0] . '.lvl', ':my_param')
            );
            $query->setParameter('my_param', '1');
            return $query;
        } else {
            return parent::createQuery($context);
        }
    }

    protected function configureDatagridFilters(DatagridMapper $filter) {
        parent::configureDatagridFilters($filter);
    }

    protected function configureFormFields(FormMapper $form) {
        $subject = $this->getSubject();
        $id = $subject->getId();

        $form
                // ***** Configuration du menu
                ->with('Général', array(
                    'class' => 'col-md-4',
                    'box_class' => 'box box-solid box-danger'
                ))
                ->add('label')
        ;
        if ($this->configurationPool->getContainer()->getParameter('menu_config_sous_titre')) {
            $form
                    ->add('sousTitre', null, array(
                        'required' => false
                    ))
            ;
        }
        if ($this->configurationPool->getContainer()->getParameter('menu_config_multi_level')) {
            $form
                    ->add('parent', 'entity', array(
                        'class' => 'KreatysCmsBundle:Menu',
                        'label' => 'Parent',
                        'required' => false,
                        'query_builder' => function($er) use ($id) {
                            $qb = $er->createQueryBuilder('m');
                            $qb
                            ->where('m.lvl > 0');
                            if ($id) {
                                $qb
                                ->andWhere('m.id <> :id')
                                ->setParameter('id', $id);
                            }
                            $qb
                            ->orderBy('m.lft, m.label', 'ASC');
                            return $qb;
                        },
                        'group_by' => function($val, $key, $index) {
                            if (!empty($val->getParent()->getParent())) {
                                return $val->getParent();
                            }
                        },
                        'preferred_choices' => function ($val, $key) {
                            return empty($val->getParent()->getParent());
                        }
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
                ->with('Configuration', array(
                    'class' => 'col-md-4',
                    'box_class' => 'box box-solid box-danger'
                ))
                ->add('page', 'entity', array(
                    'class' => 'KreatysCmsBundle:Page',
                    'label' => 'Page',
                    'required' => false,
                    'query_builder' => function($er) {
                        $qb = $er->createQueryBuilder('p')
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
        if ($this->securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $form
                    ->add('route_name', null, array(
                        'label' => 'Route name'
                    ))
            ;
        } else {
            $form
                    ->add('route_name', 'hidden')
            ;
        }
        $form
                ->add('url')
        ;

        if ($this->configurationPool->getContainer()->getParameter('menu_config_ancre')) {
            $form
                    ->add('ancre', 'entity', array(
                        'class' => 'KreatysCmsBundle:Ancre',
                        'label' => 'Ancre',
                        'required' => false
                    ))
            ;
        }

        $form
                ->end()
        ;
    }

    protected function configureListFields(ListMapper $list) {
        $list
                ->addIdentifier('label', null, array(
                    'label' => 'Label',
                ))
                ->add('page', null, array(
                    'label' => 'Page'
                ))
                ->add('url', null, array(
                    'label' => 'Url'
                ))
                ->add('enabled', null, array(
                    'label' => 'En ligne',
                    'editable' => true
                ))
        ;
    }

    protected function configureRoutes(RouteCollection $collection) {
        parent::configureRoutes($collection);

        $collection->add('tree', 'tree');
        $collection->remove('show');
    }

    public function prePersist($object) {
        // on ajoute le menu racine si il existe pas
        $this->menuManager->setMenuRacine();
        // si pas de parent on le lie au menu racine
        if (empty($object->getParent())) {
            $object->setParent($this->menuManager->getMenuRacine());
        }
    }

    public function preUpdate($object) {
        // si pas de parent on le lie au menu racine
        if (empty($object->getParent())) {
            $object->setParent($this->menuManager->getMenuRacine());
        }
    }

    /**
     * @param CmsMenuManager $menuManager
     */
    public function setMenuManager(CmsMenuManager $menuManager) {
        $this->menuManager = $menuManager;
    }

    public function setSecurityContext(SecurityContext $securityContext) {
        $this->securityContext = $securityContext;
    }

}
