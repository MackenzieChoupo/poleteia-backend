<?php
namespace Kreatys\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sonata\TranslationBundle\Model\Gedmo\AbstractTranslatable;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of Block
 *
 * @author remi
 */
/**
 * @Gedmo\Tree(type="nested")
 * @ORm\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 * @ORM\Table(name="kcms_block")
 * @ORM\HasLifecycleCallbacks()
 */
class Block extends AbstractTranslatable implements TranslatableInterface {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToMany(targetEntity="Block", mappedBy="parent", cascade={"persist"})
     * @ORM\OrderBy({"lft" = "ASC"})
     **/
    protected $children;
    
    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Block", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parent;
    
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
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="blocks")
     */
    protected $page;
    
    /**
     * @ORM\OneToOne(targetEntity="Ancre", mappedBy="block", cascade={"persist", "remove"})
     */
    protected $ancre;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Translatable
     **/
    protected $contents;
    
    /**
     * @ORM\Column(type="array", nullable=true)
     **/
    protected $settings;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $type;
    
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
        $this->contents = serialize(array());
        $this->children = new ArrayCollection();
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     *
     * @return Block
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     *
     * @return Block
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     *
     * @return Block
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param integer $root
     *
     * @return Block
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return integer
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set contents
     *
     * @param array $contents
     *
     * @return Block
     */
    public function setContents($contents)
    {
        $this->contents = serialize($contents);

        return $this;
    }

    /**
     * Get contents
     *
     * @return array
     */
    public function getContents()
    {
        return unserialize($this->contents);
    }

    /**
     * Set settings
     *
     * @param array $settings
     *
     * @return Block
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Get settings
     *
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }
    
    /**
     * Get value of a setting
     * @param string $name
     * @return string
     */
    public function getSetting($name)
    {
        return (isset($this->settings[$name])) ? $this->settings[$name] : null;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Block
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Block
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Block
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Block
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Block
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Add child
     *
     * @param \Kreatys\CmsBundle\Entity\Block $child
     *
     * @return Block
     */
    public function addChild(\Kreatys\CmsBundle\Entity\Block $child)
    {
        $this->children[] = $child;
        $child->setParent($this);

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Kreatys\CmsBundle\Entity\Block $child
     */
    public function removeChild(\Kreatys\CmsBundle\Entity\Block $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \Kreatys\CmsBundle\Entity\Block $parent
     *
     * @return Block
     */
    public function setParent(\Kreatys\CmsBundle\Entity\Block $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Kreatys\CmsBundle\Entity\Block
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set page
     *
     * @param \Kreatys\CmsBundle\Entity\Page $page
     *
     * @return Block
     */
    public function setPage(\Kreatys\CmsBundle\Entity\Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Kreatys\CmsBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }
    
    public function switchEnabled() {
        $this->setEnabled($this->getEnabled() ? false : true);
    }

    /**
     * Set ancre
     *
     * @param \Kreatys\CmsBundle\Entity\Ancre $ancre
     * @return Block
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
