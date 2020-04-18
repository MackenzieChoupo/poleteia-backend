<?php

namespace Kreatys\CmsBundle\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Kreatys\CmsBundle\Entity\Block;

interface BlockServiceInterface
{
    /**
     * Init contents & settings
     */
    public function init($front = false);
    
    /**
     * @return array
     */
    public function getContents();
    
    /**
     * @return array
     */
    public function getSettings();
    
    /**
     * @param FormMapper $formMapper
     * @param Block $block
     * @return void
     */
    public function buildEditForm(FormMapper $formMapper, Block $block);
    
    /**
     * @param FormMapper $formMapper
     * @param Block $block
     * @return void
     */
    public function buildCreateForm(FormMapper $formMapper, Block $block);
    
    /**
     * @param FormMapper $formMapper
     * @param Block $block
     * @return void
     */
    public function buildEditContentsForm(FormMapper $formMapper, Block $block);    
            
    /**
     * @return string
     */
    public function render(Block $block, $front = false);
    
    /**
     * @return boolean
     */
    public function hasProcessRequest();
    
    /**
     * @param Request $request
     * @param Block $bloc
     */
    public function processRequest(Request $request, Block $bloc);
    
    /**
     * @param ErrorElement $errorElement
     * @param \Kreatys\CmsBundle\Block\BlockInterface $block
     * @return void
     */
    //public function validateBlock(ErrorElement $errorElement, Block $block);
    
    /**
     * @param OptionsResolverInterface $resolver
     * @return void
     */
    //public function setDefaultSettings(OptionsResolverInterface $resolver);
    
    /**
     * @param \Kreatys\CmsBundle\Entity\Block $block
     */
    public function load(Block $block);
    
    /**
     * @param $media
     */
    public function getJavascripts($media);
    
    /**
     * @param $media
     */
    public function getStylesheets($media);    
    
    /**
     * @return string
     */
    public function getName();    
    
    
    /**
     * @return string
     */
    public function getTemplate();
}
