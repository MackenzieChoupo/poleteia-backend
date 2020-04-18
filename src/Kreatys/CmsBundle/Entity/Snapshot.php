<?php

namespace Kreatys\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sonata\TranslationBundle\Model\Gedmo\AbstractTranslatable;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;

/**
 * @ORM\Entity(repositoryClass="Kreatys\CmsBundle\Repository\SnapshotRepository")
 * @ORM\Table(name="kcms_snapshot")
 * @ORM\HasLifecycleCallbacks()
 */
class Snapshot extends AbstractTranslatable implements TranslatableInterface {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Page", inversedBy="snapshot")
     */
    protected $page;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Translatable
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Translatable
     */
    protected $slug;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     * @Gedmo\Translatable
     */
    protected $url;

    /**
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    protected $route_name;

    /**
     * @ORM\Column(type="array")
     */
    protected $route_options = array();

    /**
     * @ORM\Column(type="array")
     */
    protected $route_requirements = array();

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Translatable
     */
    protected $title;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Translatable
     */
    protected $meta_title;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Translatable
     */
    protected $meta_keywords;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Translatable
     */
    protected $meta_description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $google_analitics;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $stylesheets;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $javascripts;

    /**
     * @ORM\Column(type="boolean", options={"default"=true})
     */
    protected $breadcrumb_link = true;

    /**
     * @ORM\Column(type="boolean", options={"default"=true})
     */
    protected $viewTitle = true;

    /**
     * @ORM\Column(type="boolean", options={"default"=true})
     */
    protected $breadcrumb = true;

    /**
     * @ORM\OneToOne(targetEntity="Page")
     */
    protected $redirect;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Translatable
     */
    protected $content;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    protected $enabled;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $connexion;

    /**
     * @ORM\ManyToOne(targetEntity="Page")
     */
    protected $redirect_connexion;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    protected $redirect_session;
    
    protected $filAriane = array();
    
    private $contentHtml = '';

    public function __construct() {
        $this->setCreated(new \DateTime());
        $this->setUpdated(new \DateTime());
        $this->content = serialize(array());
    }
    
    /**
     * @return string
     */
    function getContentHtml()
    {
        return $this->contentHtml;
    }

    /**
     * @param string $contentHtml
     * @return \Kreatys\CmsBundle\Entity\Snapshot
     */
    function setContentHtml($contentHtml)
    {
        $this->contentHtml = $contentHtml;
        return $this;
    }

    
    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue() {
        $this->setUpdated(new \DateTime());
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Snapshot
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Snapshot
     */
    public function setSlug($slug) {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Snapshot
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Snapshot
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return Snapshot
     */
    public function setMetaTitle($metaTitle) {
        $this->meta_title = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string
     */
    public function getMetaTitle() {
        return $this->meta_title;
    }

    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     *
     * @return Snapshot
     */
    public function setMetaKeywords($metaKeywords) {
        $this->meta_keywords = $metaKeywords;

        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return string
     */
    public function getMetaKeywords() {
        return $this->meta_keywords;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     *
     * @return Snapshot
     */
    public function setMetaDescription($metaDescription) {
        $this->meta_description = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription() {
        return $this->meta_description;
    }

    /**
     * Set content
     *
     * @param json $content
     *
     * @return Snapshot
     */
    public function setContent($content) {
        $this->content = serialize($content);

        return $this;
    }

    /**
     * Get content
     *
     * @return json
     */
    public function getContent() {
        return unserialize($this->content);
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Snapshot
     */
    public function setCreated($created) {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Snapshot
     */
    public function setUpdated($updated) {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated() {
        return $this->updated;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Snapshot
     */
    public function setEnabled($enabled) {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled() {
        return $this->enabled;
    }

    /**
     * Set page
     *
     * @param \Kreatys\CmsBundle\Entity\Page $page
     *
     * @return Snapshot
     */
    public function setPage(\Kreatys\CmsBundle\Entity\Page $page = null) {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Kreatys\CmsBundle\Entity\Page
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * Set googleAnalitics
     *
     * @param string $googleAnalitics
     *
     * @return Snapshot
     */
    public function setGoogleAnalitics($googleAnalitics) {
        $this->google_analitics = $googleAnalitics;

        return $this;
    }

    /**
     * Get googleAnalitics
     *
     * @return string
     */
    public function getGoogleAnalitics() {
        return $this->google_analitics;
    }

    /**
     * Set stylesheets
     *
     * @param string $stylesheets
     *
     * @return Snapshot
     */
    public function setStylesheets($stylesheets) {
        $this->stylesheets = $stylesheets;

        return $this;
    }

    /**
     * Get stylesheets
     *
     * @return string
     */
    public function getStylesheets() {
        return $this->stylesheets;
    }

    /**
     * Set javascripts
     *
     * @param string $javascripts
     *
     * @return Snapshot
     */
    public function setJavascripts($javascripts) {
        $this->javascripts = $javascripts;

        return $this;
    }

    /**
     * Get javascripts
     *
     * @return string
     */
    public function getJavascripts() {
        return $this->javascripts;
    }

    /**
     * Set breadcrumb
     *
     * @param boolean $breadcrumb
     *
     * @return Snapshot
     */
    public function setBreadcrumb($breadcrumb) {
        $this->breadcrumb = $breadcrumb;

        return $this;
    }

    /**
     * Get breadcrumb
     *
     * @return boolean
     */
    public function getBreadcrumb() {
        return $this->breadcrumb;
    }

    /**
     * Set redirect
     *
     * @param \Kreatys\CmsBundle\Entity\Page $redirect
     *
     * @return Snapshot
     */
    public function setRedirect(\Kreatys\CmsBundle\Entity\Page $redirect = null) {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * Get redirect
     *
     * @return \Kreatys\CmsBundle\Entity\Page
     */
    public function getRedirect() {
        return $this->redirect;
    }

    
    
    /**
     * 
     */
    public function initFilAriane() {
        $this->createFilAriane($this->getPage());
        return count($this->filAriane);
    }
    /**
     * @return Snapshot
     */
    public function setFilAriane($filAriane) {
        $this->filAriane = $filAriane;
        return $this;
    }
    /**
     * @return array
     */
    public function getFilAriane() {        
        return $this->filAriane;
    }

    public function createFilAriane(Page $object) {
        array_unshift($this->filAriane, array('title' => $object->getTitle(), 'enabled' => $object->getEnabled(), 'route' => $object->getRouteName(), 'breadcrumbLink' => $object->getBreadcrumbLink()));
        if ($object->getParent() !== null) {
            $this->createFilAriane($object->getParent());
        }
    }

    /**
     * Set routeName
     *
     * @param string $routeName
     *
     * @return Snapshot
     */
    public function setRouteName($routeName) {
        $this->route_name = $routeName;

        return $this;
    }

    /**
     * Get routeName
     *
     * @return string
     */
    public function getRouteName() {
        return $this->route_name;
    }

    /**
     * Set connexion
     *
     * @param string $connexion
     *
     * @return Snapshot
     */
    public function setConnexion($connexion) {
        $this->connexion = $connexion;

        return $this;
    }

    /**
     * Get connexion
     *
     * @return string
     */
    public function getConnexion() {
        return $this->connexion;
    }

    /**
     * Set redirectConnexion
     *
     * @param \Kreatys\CmsBundle\Entity\Page $redirectConnexion
     *
     * @return Snapshot
     */
    public function setRedirectConnexion(\Kreatys\CmsBundle\Entity\Page $redirectConnexion = null) {
        $this->redirect_connexion = $redirectConnexion;

        return $this;
    }

    /**
     * Get redirectConnexion
     *
     * @return \Kreatys\CmsBundle\Entity\Page
     */
    public function getRedirectConnexion() {
        return $this->redirect_connexion;
    }

    /**
     * Set routeOptions
     *
     * @param string $routeOptions
     *
     * @return Snapshot
     */
    public function setRouteOptions($routeOptions) {
        $this->route_options = $routeOptions;

        return $this;
    }

    /**
     * Get routeOptions
     *
     * @return string
     */
    public function getRouteOptions() {
        return $this->route_options;
    }

    public function getFormatedRouteOptions() {
        $data = array();
        foreach ($this->getRouteOptions() as $option) {
            $data[$option['key']] = $option['value'];
        }
        return $data;
    }


    /**
     * Set routeRequirements
     *
     * @param array $routeRequirements
     *
     * @return Snapshot
     */
    public function setRouteRequirements($routeRequirements)
    {
        $this->route_requirements = $routeRequirements;

        return $this;
    }

    /**
     * Get routeRequirements
     *
     * @return array
     */
    public function getRouteRequirements()
    {
        return $this->route_requirements;
    }

    public function getFormatedRouteRequirements() {
        $data = array();
        foreach ($this->getRouteRequirements() as $requirement) {
            $data[$requirement['key']] = $requirement['value'];
        }
        return $data;
    }


    /**
     * Set redirect_session
     *
     * @param boolean $redirectSession
     * @return Snapshot
     */
    public function setRedirectSession($redirectSession)
    {
        $this->redirect_session = $redirectSession;

        return $this;
    }

    /**
     * Get redirect_session
     *
     * @return boolean 
     */
    public function getRedirectSession()
    {
        return $this->redirect_session;
    }

    /**
     * Set breadcrumb_link
     *
     * @param boolean $breadcrumbLink
     * @return Snapshot
     */
    public function setBreadcrumbLink($breadcrumbLink)
    {
        $this->breadcrumb_link = $breadcrumbLink;

        return $this;
    }

    /**
     * Get breadcrumb_link
     *
     * @return boolean 
     */
    public function getBreadcrumbLink()
    {
        return $this->breadcrumb_link;
    }

    /**
     * Set viewTitle
     *
     * @param boolean $viewTitle
     * @return Snapshot
     */
    public function setViewTitle($viewTitle)
    {
        $this->viewTitle = $viewTitle;

        return $this;
    }

    /**
     * Get viewTitle
     *
     * @return boolean 
     */
    public function getViewTitle()
    {
        return $this->viewTitle;
    }
}
