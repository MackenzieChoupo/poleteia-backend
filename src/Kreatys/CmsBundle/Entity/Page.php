<?php

namespace Kreatys\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sonata\TranslationBundle\Model\Gedmo\AbstractTranslatable;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of Page
 *
 * @author remi
 */

/**
 * @Gedmo\Tree(type="nested")
 * @ORm\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 * @ORM\Table(name="kcms_page")
 * @ORM\HasLifecycleCallbacks()
 */
class Page extends AbstractTranslatable implements TranslatableInterface {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent")
     * */
    protected $children;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer", nullable=true)
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer", nullable=true)
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer", nullable=true)
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Block", mappedBy="page", cascade={"remove"})
     * @ORM\OrderBy({"lft" = "ASC"})
     * */
    protected $blocks;

    /**
     * @ORM\OneToOne(targetEntity="Snapshot", mappedBy="page", cascade={"remove"})
     */
    protected $snapshot;

    /**
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="page", cascade={"remove"})
     */
    protected $menu;

    /**
     * @ORM\OneToOne(targetEntity="Page")
     */
    protected $redirect;

    /**
     * @ORM\Column(type="boolean", options={"default"=true})
     */
    protected $breadcrumb_link = true;

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
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @Gedmo\Translatable
     */
    protected $url;

    /**
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    protected $route_name;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Translatable
     */
    protected $title;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $custom_url;

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
    protected $viewTitle = true;

    /**
     * @ORM\Column(type="boolean", options={"default"=true})
     */
    protected $breadcrumb = true;

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
     * @ORM\Column(type="boolean", options={"default"=true})
     */
    protected $edited;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    protected $speciale = false;

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
    protected $redirect_session = false;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $url_suffixe;

    /**
     * @ORM\Column(type="array")
     */
    protected $route_options = array();

    /**
     * @ORM\Column(type="array")
     */
    protected $route_requirements = array();
    
    
    protected $filAriane = array();

    public function __construct() {
        $this->setCreated(new \DateTime());
        $this->setUpdated(new \DateTime());
        $this->children = new ArrayCollection();
        $this->blocks = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue() {
        $this->setUpdated(new \DateTime());
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->getName() != "" ? $this->getName() : "Nouvelle page";
    }

    /**
     * @return Block
     */
    public function getMasterBlock() {
        foreach ($this->blocks as $block) {
            if ($block->getLvl() == 0) {
                return $block;
            }
        }

        return null;
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
     * Set lft
     *
     * @param integer $lft
     *
     * @return Page
     */
    public function setLft($lft) {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft() {
        return $this->lft;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     *
     * @return Page
     */
    public function setLvl($lvl) {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl() {
        return $this->lvl;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     *
     * @return Page
     */
    public function setRgt($rgt) {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt() {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param integer $root
     *
     * @return Page
     */
    public function setRoot($root) {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return integer
     */
    public function getRoot() {
        return $this->root;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * Set customUrl
     *
     * @param string $customUrl
     *
     * @return Page
     */
    public function setCustomUrl($customUrl) {
        $this->custom_url = $customUrl;

        return $this;
    }

    /**
     * Get customUrl
     *
     * @return string
     */
    public function getCustomUrl() {
        return $this->custom_url;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * Set edited
     *
     * @param boolean $edited
     *
     * @return Page
     */
    public function setEdited($edited) {
        $this->edited = $edited;

        return $this;
    }

    /**
     * Get edited
     *
     * @return boolean
     */
    public function getEdited() {
        return $this->edited;
    }

    /**
     * Add child
     *
     * @param \Kreatys\CmsBundle\Entity\Page $child
     *
     * @return Page
     */
    public function addChild(\Kreatys\CmsBundle\Entity\Page $child) {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Kreatys\CmsBundle\Entity\Page $child
     */
    public function removeChild(\Kreatys\CmsBundle\Entity\Page $child) {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren() {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \Kreatys\CmsBundle\Entity\Page $parent
     *
     * @return Page
     */
    public function setParent(\Kreatys\CmsBundle\Entity\Page $parent = null) {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Kreatys\CmsBundle\Entity\Page
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Add block
     *
     * @param \Kreatys\CmsBundle\Entity\Block $block
     *
     * @return Page
     */
    public function addBlock(\Kreatys\CmsBundle\Entity\Block $block) {
        $this->blocks[] = $block;

        return $this;
    }

    /**
     * Remove block
     *
     * @param \Kreatys\CmsBundle\Entity\Block $block
     */
    public function removeBlock(\Kreatys\CmsBundle\Entity\Block $block) {
        $this->blocks->removeElement($block);
    }

    /**
     * Get blocks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlocks() {
        return $this->blocks;
    }

    /**
     * Set snapshot
     *
     * @param \Kreatys\CmsBundle\Entity\Snapshot $snapshot
     *
     * @return Page
     */
    public function setSnapshot(\Kreatys\CmsBundle\Entity\Snapshot $snapshot = null) {
        $this->snapshot = $snapshot;

        return $this;
    }

    /**
     * Get snapshot
     *
     * @return \Kreatys\CmsBundle\Entity\Snapshot
     */
    public function getSnapshot() {
        return $this->snapshot;
    }

    public function switchEnabled() {
        $this->setEnabled($this->getEnabled() ? false : true);
    }

    /**
     * Set googleAnalitics
     *
     * @param string $googleAnalitics
     *
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * 
     */
    public function initFilAriane() {
        $this->createFilAriane($this);
        return count($this->filAriane);
    }

    /**
     * @return array
     */
    public function getFilAriane() {
        $this->createFilAriane($this);
        return $this->filAriane;
    }

    public function createFilAriane($object) {
        array_unshift($this->filAriane, array('title' => $object->getTitle(), 'enabled' => $object->getEnabled(), 'route' => $object->getRouteName(), 'breadcrumbLink' => $object->getBreadcrumbLink()));
        if ($object->getParent() !== null) {
            $this->createFilAriane($object->getParent());
        }
    }

    /**
     * Set redirect
     *
     * @param \Kreatys\CmsBundle\Entity\Page $redirect
     *
     * @return Page
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
     * Add menu
     *
     * @param \Kreatys\CmsBundle\Entity\Menu $menu
     *
     * @return Page
     */
    public function addMenu(\Kreatys\CmsBundle\Entity\Menu $menu) {
        $this->menu[] = $menu;

        return $this;
    }

    /**
     * Remove menu
     *
     * @param \Kreatys\CmsBundle\Entity\Menu $menu
     */
    public function removeMenu(\Kreatys\CmsBundle\Entity\Menu $menu) {
        $this->menu->removeElement($menu);
    }

    /**
     * Get menu
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMenu() {
        return $this->menu;
    }

    /**
     * Set routeName
     *
     * @param string $routeName
     *
     * @return Page
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
     * Set speciale
     *
     * @param boolean $speciale
     *
     * @return Page
     */
    public function setSpeciale($speciale) {
        $this->speciale = $speciale;

        return $this;
    }

    /**
     * Get speciale
     *
     * @return boolean
     */
    public function getSpeciale() {
        return $this->speciale;
    }

    /**
     * Set connexion
     *
     * @param boolean $connexion
     *
     * @return Page
     */
    public function setConnexion($connexion) {
        $this->connexion = $connexion;

        return $this;
    }

    /**
     * Get connexion
     *
     * @return boolean
     */
    public function getConnexion() {
        return $this->connexion;
    }

    /**
     * Set redirectConnexion
     *
     * @param \Kreatys\CmsBundle\Entity\Page $redirectConnexion
     *
     * @return Page
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
     * Set urlSuffixe
     *
     * @param string $urlSuffixe
     *
     * @return Page
     */
    public function setUrlSuffixe($urlSuffixe) {
        $this->url_suffixe = $urlSuffixe;

        return $this;
    }

    /**
     * Get urlSuffixe
     *
     * @return string
     */
    public function getUrlSuffixe() {
        return $this->url_suffixe;
    }

    /**
     * Set routeOptions
     *
     * @param string $routeOptions
     *
     * @return Page
     */
    public function setRouteOptions($routeOptions) {
//        dump($routeOptions);
//        exit;
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

    /**
     * Set routeRequirements
     *
     * @param array $routeRequirements
     *
     * @return Page
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

    /**
     * Set redirect_session
     *
     * @param boolean $redirectSession
     * @return Page
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
     * @return Page
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
     * @return Page
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
