<?php

namespace Kreatys\CmsBundle\Block;

use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Kreatys\CmsBundle\Entity\Block;

abstract class BaseBlockService implements BlockServiceInterface {

    /**
     * @var string 
     */
    protected $name;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface 
     */
    protected $container;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface 
     */
    protected $templating;

    /**
     * @var array 
     */
    private $contents;

    /**
     * @var array 
     */
    private $settings;
    
    /**
     * @var boolean
     */
    private $initCalled = false;
    
    /**
     * @var \Sonata\MediaBundle\Admin\BaseMediaAdmin 
     */
    private $mediaAdmin;
    
    /**
     * @var Sonata\MediaBundle\Admin\GalleryAdmin 
     */
    private $galleryAdmin;

    /**
     * @param EngineInterface $templating
     */
    public function __construct($name, ContainerInterface $container) {
        $this->name = $name;
        $this->container = $container;
        $this->templating = $container->get('templating');
        $settings = $this->container->getParameter('block_settings');
        foreach ($settings as $key => $value) {
            foreach ($value as $key2 => $value2) {
                if (strpos($key2,'_')) {
                    $settings[$key][str_replace('_', '-', $key2)] = $value2;
                    unset($settings[$key][$key2]);
                }
            }
        }

        $this->contents = array();
        $this->settings = array(
            array('text_align', 'choice', array(
                    'label' => 'Alignement',
                    'choices' => $settings['text_align'],
                    'required' => false
                )),
            array('background', 'choice', array(
                    'label' => 'Couleur de fond',
                    'choices' => $settings['background'],
                    'required' => false,
                    'empty_value' => 'Aucune'
                )),
            array('padding_top', 'choice', array(
                    'label' => 'Espace haut (au dessus du bloc)',
                    'choices' => $settings['padding_top'],
                    'required' => false,
                    'empty_value' => 'Aucun'
                )),
            array('padding_bottom', 'choice', array(
                    'label' => 'Espace bas (au dessous du bloc)',
                    'choices' => $settings['padding_bottom'],
                    'required' => false,
                    'empty_value' => 'Aucun'
                ))
        );
    }
    
    /**
     * 
     * @param boolean $front
     */
    public function callInit($front = false)
    {
        if(!$this->initCalled) {
            $this->init($front);
            $this->initCalled = true;
        }
    }

    /**
     * 
     * @param string $name
     * @param string $type
     * @param array $options
     * @return \Kreatys\CmsBundle\Block\BaseBlockService
     */
    protected function addContent($name, $type = null, array $options = array(), array $extra = array()) {
        $this->contents[] = array($name, $type, $options, $extra);
        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $type
     * @param array $options
     * @return \Kreatys\CmsBundle\Block\BaseBlockService
     */
    protected function addSetting($name, $type = null, array $options = array(), array $extra = array()) {
        $this->settings[] = array($name, $type, $options, $extra);
        return $this;
    }

    /**
     * 
     * @return array
     */
    public function getContents() {
        return $this->contents;
    }

    /**
     * 
     * @return array
     */
    public function getSettings() {
        return $this->settings;
    }

    /**
     * 
     */
    protected function clearContents() {
        $this->contents = array();
    }

    /**
     * 
     */
    protected function clearSettings() {
        $this->settings = array();
    }

    /**
     * 
     */
    protected function removeSettings($settingName) {
        if (is_array($settingName)) {
            foreach ($settingName as $name) {
                $this->removeSettings($name);
            }
        } else {
            foreach ($this->settings as $key => $setting) {
                if ((array_search($settingName, $setting)) !== false) {
                    array_splice($this->settings, $key, 1);
                }
            }
        }
    }

    /**
     * @param FormMapper $formMapper
     * @param Block $block
     */
    public function buildCreateForm(FormMapper $formMapper, Block $block) {
//        $this->buildEditForm($formMapper, $block);
        $formMapper
                ->with('Contenu du bloc', array(
                    'class' => 'col-md-8',
                    'box_class' => 'box box-solid box-danger'
                ))
                ->add('contents', 'sonata_type_immutable_array', array(
                    'label' => false,
                    'keys' => $this->processSpeficicFormType($this->contents, $formMapper)
                ))
                ->end()
                ->with('Paramètres du bloc', array(
                    'class' => 'col-md-4',
                    'box_class' => 'box box-solid box-danger'
                ))
                ->add('settings', 'sonata_type_immutable_array', array(
                    'label' => false,
                    'keys' => $this->processSpeficicFormType($this->settings, $formMapper)
                ))
                ->end();
    }

    /**
     * @param FormMapper $formMapper
     * @param Block $block
     */
    public function buildEditForm(FormMapper $formMapper, Block $block) {
//        $this->buildCreateForm($formMapper, $block);
        $formMapper
//                ->with('Contenu du bloc', array(
//                    'class' => 'col-md-8',
//                    'box_class' => 'box box-solid box-danger'
//                ))
//                ->add('contents', 'sonata_type_immutable_array', array(
//                    'label' => false,
//                    'keys' => $this->contents
//                ))
//                ->end()
//                ->with('Paramètres du bloc', array(
//                    'class' => 'col-md-4',
//                    'box_class' => 'box box-solid box-danger'
//                ))
                ->add('settings', 'sonata_type_immutable_array', array(
                    'label' => false,
                    'keys' => $this->processSpeficicFormType($this->settings, $formMapper)
        ));
//                ->end();
    }
    
    
    public function buildEditContentsForm(FormMapper $formMapper, Block $block) {
        $formMapper
                ->add('contents', 'sonata_type_immutable_array', array(
                    'label' => false,
                    'keys' => $this->processSpeficicFormType($this->contents, $formMapper)
                ))
        ;
        
    }
    
    protected function processSpeficicFormType(array $fields, FormMapper $formMapper)
    {
        foreach($fields as $i => $field) {            
            if($field[1] == 'kcms_media') {
                $fields[$i] = array($this->getMediaBuilder($field[0], $formMapper, $field[3]), null, $field[2], $field[3]);
            } else if($field[1] == 'kcms_gallery') {
                $fields[$i] = array($this->getGalleryBuilder($field[0], $formMapper, $field[3]), null, $field[2], $field[3]);
            }
        }
        
        return $fields;
    }
    
    protected function getGalleryBuilder($fieldName, FormMapper $formMapper, $extraParameters = array())
    {
        // simulate an association ...
        $fieldDescription = $this->getGalleryAdmin()->getModelManager()->getNewFieldDescriptionInstance($this->galleryAdmin->getClass(), 'gallery', $extraParameters);
        $fieldDescription->setAssociationAdmin($this->getGalleryAdmin());
        $fieldDescription->setAdmin($formMapper->getAdmin());
        $fieldDescription->setOption('edit', 'list');
        $fieldDescription->setAssociationMapping(array(
            'fieldName' => 'gallery',
            'type'      => \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_ONE,
        ));

        return $formMapper->create($fieldName, 'sonata_type_model_list', array(
            'sonata_field_description' => $fieldDescription,
            'class'                    => $this->getGalleryAdmin()->getClass(),
            'model_manager'            => $this->getGalleryAdmin()->getModelManager(),
        ));
    }
    
    protected function getMediaBuilder($fieldName, FormMapper $formMapper, $extraParameters = array())
    {
        // simulate an association ...
        $fieldDescription = $this->getMediaAdmin()->getModelManager()->getNewFieldDescriptionInstance($this->mediaAdmin->getClass(), 'media', $extraParameters);
        $fieldDescription->setAssociationAdmin($this->getMediaAdmin());
        $fieldDescription->setAdmin($formMapper->getAdmin());
        $fieldDescription->setOption('edit', 'list');
        $fieldDescription->setAssociationMapping(array(
            'fieldName' => 'media',
            'type'      => \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_ONE,
        ));

        return $formMapper->create($fieldName, 'sonata_type_model_list', array(
            'sonata_field_description' => $fieldDescription,
            'class'                    => $this->getMediaAdmin()->getClass(),
            'model_manager'            => $this->getMediaAdmin()->getModelManager(),
        ));
    }
    
    protected function getGalleryAdmin()
    {
        if (!$this->galleryAdmin) {
            $this->galleryAdmin = $this->container->get('sonata.media.admin.gallery');
        }

        return $this->galleryAdmin;
    }
    
    protected function getMediaAdmin()
    {
        if (!$this->mediaAdmin) {
            $this->mediaAdmin = $this->container->get('sonata.media.admin.media');
        }

        return $this->mediaAdmin;
    }

    /**
     * @return boolean
     */
    public function hasProcessRequest() {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function processRequest(Request $request, Block $block) {
        
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist(Block $block) {
        
    }

    /**
     * {@inheritdoc}
     */
    public function postPersist(Block $block) {
        
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate(Block $block) {
        
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate(Block $block) {
        
    }

    /**
     * {@inheritdoc}
     */
    public function preRemove(Block $block) {
        
    }

    /**
     * {@inheritdoc}
     */
    public function postRemove(Block $block) {
        
    }

    /**
     * {@inheritdoc}
     */
    public function load(Block $block) {
        
    }

    /**
     * {@inheritdoc}
     */
    public function getJavascripts($media = null) {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getStylesheets($media = null) {
        return array();
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return EngineInterface
     */
    public function getTemplating() {
        return $this->templating;
    }

}
