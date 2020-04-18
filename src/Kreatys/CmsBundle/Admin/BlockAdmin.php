<?php

namespace Kreatys\CmsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Kreatys\CmsBundle\Block\BlockServiceManager;
use Kreatys\CmsBundle\Block\Service\ContainerBlockService;
use Kreatys\CmsBundle\Manager\CmsPageManager;
use Symfony\Component\Security\Core\SecurityContext;
use Kreatys\CmsBundle\Entity\Ancre;

class BlockAdmin extends Admin {

    const EDIT_CONTEXT_SETTINGS = 'settings';
    const EDIT_CONTEXT_CONTENTS = 'cotnents';

    /**
     * @var string 
     */
    protected $baseRoutePattern = 'blocks';

    /**
     * @var \Kreatys\CmsBundle\Block\BlockServiceManager 
     */
    protected $blockManager;

    /**
     * @var \Symfony\Component\Security\Core\SecurityContext 
     */
    protected $securityContext;

    /**
     * @var \Kreatys\CmsBundle\Manager\CmsPageManager 
     */
    protected $cmsPageManager;

    /**
     * @var array 
     */
    protected $locales;
//    private $defaultBlockService = 'kreatys_cms.block.service.title_text';
    public $newBlockPageId = 0;
    public $newBlockParentId = null;
    public $editContext = self::EDIT_CONTEXT_SETTINGS;

    public function getFormTheme()
    {        
        return array_merge(
            parent::getFormTheme(),
            array('KreatysCmsBundle:Admin:CRUD/block.html.twig')
        );        
    }
    
    /**
     * @param FormMapper $form
     */
    protected function configureFormFields(FormMapper $form) {
        $block = $this->getSubject();
        if ($block->getId() === null) {
            if ($this->getRequest()->getMethod() === 'POST') {
                $data = $this->getRequest()->get($this->uniqid);
                $block->setName($data['name']);
                $block->setType($data['type']);
                if (isset($data['settings'])) {
                    $block->setSettings($data['settings']);
                }
            } else {
                $block->setType(($this->getRequest()->get('type') != '') ? $this->getRequest()->get('type') : $this->getConfigurationPool()->getContainer()->getParameter('block_default'));
                $block->setName($this->getRequest()->get('name', ''));
            }
            
            $service = $this->blockManager->getService($block->getType());
            $service->callInit();

            $form
                    ->with('Global')
                    ->add('name', null, array(
                        'label' => 'Nom',
                        'attr' => array('class' => 'name'),
                        'data' => $this->blockManager->getName($block->getType())
                    ))
                    ->add('page_id', 'hidden', array('mapped' => false, 'data' => $this->newBlockPageId))    // a remplacer par sonata_type_model_hidden
                    ->add('block_id', 'hidden', array('mapped' => false, 'data' => $this->newBlockParentId))  // a remplacer par sonata_type_model_hidden
                    ->add('enabled', 'hidden', array('data' => 1))
                    ->end()
                    ->with('Type de bloc', array(
                        'class' => 'col-md-8',
                    ))
                    ->add('type', 'block_choice_type', array(
                        'label' => false,
                        'choices' => $this->blockManager->getTypes($this->securityContext),
                        'required' => true,
                        'attr' => array('class' => 'type')
                    ))
                    ->end()
            ;
            if (!empty($service->getSettings())) {
                $form
                        ->with('Paramètres du bloc', array(
                            'class' => 'col-md-4',
                        ))
                        ->add('settings', 'sonata_type_immutable_array', array(
                            'mapped' => false,
                            'label' => false,
                            'keys' => $service->getSettings()
                        ))
                        ->end()
                ;
            }
        } else {
            $service = $this->blockManager->get($block);
            $service->callInit();

            if ($this->editContext == self::EDIT_CONTEXT_SETTINGS) {
                $service->buildEditForm($form, $block);
            } else {
                $service->buildEditContentsForm($form, $block);
            }
        }
    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list) {
        $list
                ->add('name')
                ->add('type', 'string', array('template' => 'KreatysCmsBundle:Block:Admin/list_field_type.html.twig'))
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'view' => array(),
                        'edit' => array(),
                        'delete' => array(),
                    )
                ))
        ;
    }

    /**
     * @param ShowMapper $filter
     */
    protected function configureShowFields(ShowMapper $filter) {
        $filter
                ->with('General')
                ->add('name')
                ->add('type')
                ->add('enabled')
                ->add('position')
                ->end()
                ->with('Contents')
                ->add('contents', null, array(
                    'template' => 'KreatysCmsBundle:Block:Admin/view.html.twig'
                ))
                ->end()
        ;
    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection) {
        //$collection->remove('create');
        //$collection->remove('edit');
        //$collection->remove('delete');        
    }

    /**
     * {@inheritdoc}
     */
    public function postPersist($object) {       
        $this->blockManager->get($object)->postPersist($object);

        $localeObject = $object->getLocale();
        $originalContent = $object->getContents();
        $originalContent['_trans_copy'] = $localeObject;
        foreach ($this->locales as $locale) {
            if ($locale !== $localeObject) {
                $object->setContents($originalContent);
                $object->setLocale($locale);
                $this->getModelManager()->update($object);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object) {
        // TODO : a voir une maniere plus élégante d'associer la page a un bloc 
        // plutot que de passer un paramètre page_id
        $keys = $this->getRequest()->request->keys();
        $datas = $this->getRequest()->request->get($keys[0]);
        $page_id = (int) $datas['page_id'];
        $block_id = (int) $datas['block_id'];

        if ($page_id === 0) {
            throw new \RuntimeException('The Block must be attached to a Page');
        }

        $page = $this->cmsPageManager->getPageById($page_id);
        $page->setEdited(true);

        $object->setPage($page);


        // TODO : Mettre ce code dans un manager        
        $this->blockManager->get($object)->callInit();        
        $defaultContents = array();
        foreach ($this->blockManager->get($object)->getContents() as $content) {
            $contentData = null;
            if(isset($content[2]['data'])) {
                $contentData = $content[2]['data'];
            } else if ($content[1] == 'text' || $content[1] == 'textarea') {
                $contentData = 'Texte à remplacer';
            }
            
            $defaultContents[$content[0]] = $contentData;
        }
        $object->setContents($defaultContents);

        /* $defaultSettings = array();
          foreach($this->blockManager->get($object)->getSettings() as $setting) {
          $defaultSettings[$setting[0]] = isset($setting[2]['data']) ? $setting[2]['data'] : '';
          }
          $object->setSettings($defaultSettings); */

        if ($block_id > 0) {
            $parent = $this->getModelManager()->find($this->getClass(), $block_id);
            $object->setParent($parent);
        } else {
            $object->setParent($page->getMasterBlock());
        }
        
        $blockContainerType = $this->getConfigurationPool()->getContainer()->getParameter('block_conteneur');
        $blockContainerAnchorType = $this->getConfigurationPool()->getContainer()->getParameter('block_conteneur_anchor');

        if ($object->getType() === $blockContainerType || $object->getType() === $blockContainerAnchorType) {
            $layout = $object->getSetting('layout');
            switch ($layout) {
                case ContainerBlockService::TYPE_UN_TIERS_UN_TIERS_UN_TIERS:
                    $firstBlock = $this->getNewInstance();
                    $firstBlock->setType($blockContainerType);
                    $firstBlock->setPage($page);
                    $firstBlock->setName('colonne 1');
                    $firstBlock->setEnabled(true);
                    $object->addChild($firstBlock);
                    $secondBlock = $this->getNewInstance();
                    $secondBlock->setType($blockContainerType);
                    $secondBlock->setPage($page);
                    $secondBlock->setName('colonne 2');
                    $secondBlock->setEnabled(true);
                    $object->addChild($secondBlock);
                    $thirdBlock = $this->getNewInstance();
                    $thirdBlock->setType($blockContainerType);
                    $thirdBlock->setPage($page);
                    $thirdBlock->setName('colonne 3');
                    $thirdBlock->setEnabled(true);
                    $object->addChild($thirdBlock);
                    break;
                case ContainerBlockService::TYPE_LARGEUR_TOTAL:
                    $firstBlock = $this->getNewInstance();
                    $firstBlock->setType($blockContainerType);
                    $firstBlock->setPage($page);
                    $firstBlock->setName('colonne 1');
                    $firstBlock->setEnabled(true);
                    $object->addChild($firstBlock);
                    break;
                default:
                    $firstBlock = $this->getNewInstance();
                    $firstBlock->setType($blockContainerType);
                    $firstBlock->setPage($page);
                    $firstBlock->setName('colonne 1');
                    $firstBlock->setEnabled(true);
                    $object->addChild($firstBlock);
                    $secondBlock = $this->getNewInstance();
                    $secondBlock->setType($blockContainerType);
                    $secondBlock->setPage($page);
                    $secondBlock->setName('colonne 2');
                    $secondBlock->setEnabled(true);
                    $object->addChild($secondBlock);
                    break;
            }
        }
        
        if ($object->getType() === $blockContainerAnchorType) {
            $ancre = new Ancre();
            $settings = $object->getSettings();
            $ancre->setNom($settings['anchor']);
            $ancre->setBlock($object);
            $object->setAncre($ancre);
            $settings['anchor'] = $ancre->getNom();
            $object->setSettings($settings);
        }

        $this->blockManager->get($object)->prePersist($object);
    }

    /**
     * {@inheritdoc}
     */
    public function postRemove($object) {
        $this->blockManager->get($object)->postRemove($object);
    }

    /**
     * {@inheritdoc}
     */
    public function preRemove($object) {
        $this->blockManager->get($object)->preRemove($object);
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate($object) {
        $this->blockManager->get($object)->postUpdate($object);
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object) {
        $object->getPage()->setEdited(true);
        $blockContainerAnchorType = $this->getConfigurationPool()->getContainer()->getParameter('block_conteneur_anchor');
        if ($object->getType() === $blockContainerAnchorType) {
            $ancre = $object->getAncre();
            $settings = $object->getSettings();
            $ancre->setNom($settings['anchor']);
            $settings['anchor'] = $ancre->getNom();
            $object->setSettings($settings);
        }
        $this->blockManager->get($object)->preUpdate($object);
    }

    /**
     * @param BlockServiceManager $blockManager
     */
    public function setBlockManager(BlockServiceManager $blockManager) {
        $this->blockManager = $blockManager;
    }

    /**
     * @param CmsPageManager $cmsPageManager
     */
    public function setCmsPageManager(CmsPageManager $cmsPageManager) {

        $this->cmsPageManager = $cmsPageManager;
    }

    /**
     * @param SecurityContext $securityContext
     */
    public function setSecurityContext(SecurityContext $securityContext) {
        $this->securityContext = $securityContext;
    }

    /**
     * @param array $locales
     */
    public function setLocales(array $locales) {
        $this->locales = $locales;
    }

}
