<?php

namespace Politeia\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Politeia\CoreBundle\Form\Type\PhotoSignalementFileType;
use Politeia\CoreBundle\Entity\Signalement;

class SignalementModerateAdmin extends Admin
{
    protected $baseRouteName = 'admin_politeia_core_signalement_list_moderer';
    
    protected $baseRoutePattern = 'signalements-moderer';
    
    protected $datagridValues = array(
        
        // display the first page (default = 1)
        '_page'       => 1,
        
        // reverse order (default = 'ASC')
        '_sort_order' => 'DESC',
        
        // name of the ordered field (default = the model's id field, if any)
        '_sort_by'    => 'created',
    );
    protected $listeEtat = array(
        Signalement::ETAT_SIGNALE => 'Nouveau',
        Signalement::ETAT_VU      => 'Vu',
        Signalement::ETAT_ENCOURS => 'En cours de traitement',
        Signalement::ETAT_TRAITE  => 'Traité',
        Signalement::ETAT_ARCHIVE => 'Archivé',
    );
    
    public function getRequest() {
        if (!$this->request) {
            //  throw new \RuntimeException('The Request object has not been set');
            $this->request = $this->getConfigurationPool()->getContainer()->get('Request');
        }
        
        return $this->request;
    }
    
    public function createQuery($context = 'list') {
        $query = parent::createQuery($context);
        
        $params = array();
        $conditions = array();
        
        
        $user = $this->getUser();
        if ($user->hasRole('ROLE_MAIRIE')) {
            $query->join('o.mairie', 'm');
            $conditions[] = 'm = :mairie';
            $params['mairie'] = $user->getProfil()->getMairie();
        }
        
        $filter = $this->getRequest()->get('filter');
        if ($context === 'list' && !(isset($filter['etat']['value']) && $filter['etat']['value'] != '')) {
            $conditions[] = 'o.etat != :etatArchive';
            $params['etatArchive'] = Signalement::ETAT_ARCHIVE;
        }
        if (count($conditions) > 0) {
            foreach ($conditions as $i => $cond) {
                if ($i === 0) {
                    $query->where($cond);
                } else {
                    $query->andWhere($cond);
                }
            }
        }
        
        if ($this->getRequest()->get('_route') == 'admin_politeia_core_signalement_list') {
            $query->andWhere('o.online = 0');
        }
        
        if (count($params) > 0) {
            $query->setParameters($params);
        }
        
        return $query;
    }
    
    protected function configureDatagridFilters(DatagridMapper $filter) {
        $filter->add('titre', null, array('label' => 'Titre'))->add(
            'etat',
            null,
            array('label' => 'État'),
            'choice',
            array(
                'choices' => $this->listeEtat,
            )
        )->add(
            'created',
            'doctrine_orm_date_range',
            array('label' => 'Date', 'field_type' => 'sonata_type_date_range_picker')
        )->add(
            'online',
            null,
            array('label' => 'Modéré'),
            'choice',
            array('choices' => array(1 => 'Oui', 2 => 'Non'))
        );
    }
    
    protected function configureListFields(ListMapper $list) {
        $list->addIdentifier(
            'titre',
            'text',
            array(
                'label' => 'Titre',
            )
        )->add(
            'adresse',
            'text',
            array(
                'label' => 'Adresse',
            )
        )->add(
            'texte',
            'text',
            array(
                'label'    => 'Texte',
                'template' => 'PoliteiaCoreBundle:CRUD:list_field_texte_texte.html.twig',
            )
        )->add(
            'created',
            'date',
            array(
                'label'  => 'Signalé le',
                'format' => 'd/m/Y',
            )
        )->add(
            'citoyen.prenomNom',
            'text',
            array(
                'label' => 'Signalé par',
            )
        )->add(
            'nbConfirmation',
            'text',
            array(
                'label' => 'Nb confirmation',
            )
        )->add(
            'online',
            null,
            array(
                'label'    => 'Modéré',
                'editable' => true,
            )
        )->add(
            'etat',
            'choice',
            array(
                'label'   => 'Etat',
                'choices' => $this->listeEtat,
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
        
        $form->with(
            'Signalement',
            array(
                'class'     => 'col-md-4 col-sm-4 frm-texte',
                'box_class' => 'box box-solid box-danger',
            )
        )->add(
            'titre',
            'text',
            array(
                'label'       => 'Titre',
                'required'    => true,
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
            )
        )->add(
            'adresse',
            'text',
            array(
                'label'       => 'Adresse',
                'required'    => true,
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
            )
        )->add(
            'texte',
            'textarea',
            array(
                'label'       => 'Textes',
                'attr'        => array(
                    'rows' => 5,
                ),
                'required'    => true,
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
            )
        )->add(
            'photo',
            new \Politeia\CoreBundle\Form\Type\PhotoSignalementFileType(),
            array(
                'label'    => false,
                'required' => false,
                'mapped'   => false,
                'data'     => (int)$subject->getId() > 0 ? $subject->getPhotoName() : '',
            ),
            array(
                'type' => 'text',
            )
        )->end()->with(
            'Informations',
            array(
                'class'     => 'col-md-4 col-sm-4 frm-texte',
                'box_class' => 'box box-solid box-danger',
            )
        )->add(
            'commentaireMairie',
            'textarea',
            array(
                'label'    => 'Vos commentaires',
                'attr'     => array(
                    'rows' => 5,
                ),
                'required' => false,
            )
        )->add(
            'oldCommentaireMairie',
            'hidden',
            array(
                'mapped' => false,
                'data'   => $subject->getCommentaireMairie(),
            )
        )->end()->with(
            'Etat',
            array(
                'class'     => 'col-md-3 col-sm-3 frm-texte',
                'box_class' => 'box box-solid box-danger',
            )
        )->add(
            'etat',
            'choice',
            array(
                'label'       => 'État',
                'choices'     => $this->listeEtat,
                'multiple'    => false,
                'expanded'    => false,
                'required'    => true,
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
                'help'        => 'Un signalement archivé ne sera plus en ligne.',
            )
        )->add(
            'online',
            null,
            array(
                'label'    => 'En ligne',
                'required' => false,
            )
        )->end();
    }
    
    public function preUpdate($object) {
        $request = $this->getRequest();
        $oldCommentaireMairie = $request->request->get($request->query->get('uniqid'))['oldCommentaireMairie'];
        
        if ($oldCommentaireMairie != $object->getCommentaireMairie()) {
            $object->setCommentaireMairieUpdatedAt(new \DateTime());
        }
    }
    
    protected function configureRoutes(RouteCollection $collection) {
        $collection->remove('create');
        $collection->add('deletePhoto', $this->getRouterIdParameter() . '/delete-photo');
    }
//    
//    public function getBatchActions()
//    {
//        return array();
//    }

//    public function isGranted($name, $object = null)
//    {      
//        if($name === 'DELETE' && $object instanceof \Politeia\CoreBundle\Entity\News) {
//            return $object->isDeletable() && parent::isGranted($name, $object);
//        } else {        
//            return parent::isGranted($name, $object);
//        }
//    } 
    
    /**
     *
     * @return \Kreatys\UserBundle\Entity\User
     */
    private function getUser() {
        return $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
    }
}
