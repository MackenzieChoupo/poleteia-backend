<?php

namespace Kreatys\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Sonata\TranslationBundle\Model\Gedmo\AbstractTranslatable;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;

/**
 * Description of Menu
 *
 * @author remi
 */

/**
 * @Gedmo\Tree(type="nested")
 * @ORm\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 * @ORM\Table(name="kcms_menu")
 * @ORM\HasLifecycleCallbacks()
 */
class Menu extends AbstractTranslatable implements TranslatableInterface {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
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
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="menu")
     */
    protected $page;

    /**
     * @ORM\ManyToOne(targetEntity="Ancre", inversedBy="menus")
     */
    protected $ancre;

    /**
     * @ORM\Column(type="string", unique=false)
     * @Gedmo\Translatable
     */
    protected $label;

    /**
     * @ORM\Column(type="string", unique=false, nullable=true)
     * @Gedmo\Translatable
     */
    protected $sousTitre;

    /**
     * @ORM\Column(type="string", unique=false, nullable=true)
     */
    protected $route_name;

    /**
     * @ORM\Column(type="string", unique=false, nullable=true)
     */
    protected $url;

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

    public function __construct() {
        $this->setCreated(new \DateTime());
        $this->setUpdated(new \DateTime());
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
        return $this->getLabel() != "" ? $this->getLabel() : "Nouvel item";
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
     * @return Menu
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
     * @return Menu
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
     * @return Menu
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
     * @return Menu
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
     * Set label
     *
     * @param string $label
     *
     * @return Menu
     */
    public function setLabel($label) {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Menu
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
     * @return Menu
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
     * @return Menu
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
     * Add child
     *
     * @param \Kreatys\CmsBundle\Entity\Menu $child
     *
     * @return Menu
     */
    public function addChild(\Kreatys\CmsBundle\Entity\Menu $child) {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Kreatys\CmsBundle\Entity\Menu $child
     */
    public function removeChild(\Kreatys\CmsBundle\Entity\Menu $child) {
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
     * @param \Kreatys\CmsBundle\Entity\Menu $parent
     *
     * @return Menu
     */
    public function setParent(\Kreatys\CmsBundle\Entity\Menu $parent = null) {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Kreatys\CmsBundle\Entity\Menu
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Set page
     *
     * @param \Kreatys\CmsBundle\Entity\Page $page
     *
     * @return Menu
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
     * Set url
     *
     * @param string $url
     *
     * @return Menu
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
     * Set routeName
     *
     * @param string $routeName
     *
     * @return Menu
     */
    public function setRouteName($routeName) {
        $this->route_name = $routeName;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getRouteName() {
        return $this->route_name;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context) {
        if(empty($this->getPage()) AND empty($this->getUrl()) AND empty($this->getRouteName()) AND empty($this->getAncre())) {
            $context->buildViolation('Vous devez choisir une page ou saisir une adresse internet.')
                ->atPath('page')
                ->addViolation();
        }
    }

    public function switchEnabled() {
        $this->setEnabled($this->getEnabled() ? false : true);
    }


    /**
     * Set sousTitre
     *
     * @param string $sousTitre
     * @return Menu
     */
    public function setSousTitre($sousTitre)
    {
        $this->sousTitre = $sousTitre;

        return $this;
    }

    /**
     * Get sousTitre
     *
     * @return string 
     */
    public function getSousTitre()
    {
        return $this->sousTitre;
    }

    /**
     * Set ancre
     *
     * @param \Kreatys\CmsBundle\Entity\Ancre $ancre
     * @return Menu
     */
    public function setAncre(\Kreatys\CmsBundle\Entity\Ancre $ancre = null)
    {
        $this->ancre = $ancre;

        return $this;
    }

    /**
     * Get ancre
     *
     * @return \Kreatys\CmsBundle\Entity\Ancre 
     */
    public function getAncre()
    {
        return $this->ancre;
    }
}
