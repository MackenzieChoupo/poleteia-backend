<?php

namespace Kreatys\CmsBundle\Block\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Kreatys\CmsBundle\Block\BaseBlockService;
use Kreatys\CmsBundle\Entity\Block;

class ContactFormBlockService extends BaseBlockService {
    
    private $formType = null;
    private $formHandler = null;
    
    protected $form = null;
    protected $viewParameters = array();
    
    public function init($front = false)
    {
        $this
            ->addSetting('email_contact', 'email', array(
                    'required' => true,
                    'label' => 'Email de contact'
                )
            )
        ;
        $this->removeSettings('text_align');
        
        $this->formType = $this->container->getParameter('block_contact_form_type');
        $formHandlerName = $this->container->getParameter('block_contact_form_handler');
        if($formHandlerName !== null && $formHandlerName !== '') {
            $this->formHandler = $this->container->get($formHandlerName);
        }
        
        if($this->formType !== null &&  $this->formType !== '') {
            $this->form = $this->container->get('form.factory')->create($this->formType);
        }
        if($this->formHandler !== null) {
            $this->formHandler->init($this->viewParameters);
        }
    }

    public function render(Block $block, $front = false)
    {
        $this->viewParameters['kblock'] = $block;
        $this->viewParameters['contents'] = $block->getContents();
        $this->viewParameters['settings'] = $block->getSettings();
        $this->viewParameters['front'] = $front;       
        
        if($this->form !== null) {
            $this->viewParameters['form'] = $this->form->createView();
        }
        
        return $this->getTemplating()->render($this->getTemplate(), $this->viewParameters);
    }

    public function hasProcessRequest()
    {
        return true;
    }

    public function processRequest(Request $request, Block $block)
    {
        if ($request->isMethod('POST')) {
            if($this->formHandler !== null) {
                $this->form->handleRequest($request);
                if($this->formHandler->process($this->form, $this->container, $block, $this->viewParameters)) {
                    $this->form = $this->container->get('form.factory')->create($this->formType);
                }
            }            
        }
    }

    public function getName()
    {
        return 'Formulaire de contact';
    }

    public function getTemplate()
    {
        return $this->container->getParameter('block_contact_form');
    }
    
    public function getJavascripts($media = null)
    {
        $js = $this->container->getParameter('block_contact_javascripts');
        if(is_array($js)) {
            return $js;
        }            
    }

    public function getStylesheets($media = null)
    {
        $css = $this->container->getParameter('block_contact_stylesheets');
        if(is_array($css)) {
            return $css;
        }            
    }
}
