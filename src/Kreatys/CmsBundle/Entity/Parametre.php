<?php
namespace Kreatys\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Parametre
 *
 * @author remi
 */
/**
 * @ORM\Entity
 * @ORM\Table(name="kcms_parametre")
 * @ORM\HasLifecycleCallbacks()
 */
class Parametre {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Seo", mappedBy="parametre", cascade={"all"})
     */
    protected $seo;

    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media") 
     */
    protected $logo;

    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media") 
     */
    protected $logo_footer;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $autres;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $theme_color = 'vert';
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;
    
    protected $others = array();

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
    
    public function __toString() {
        return 'Paramètres du site';
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
     * Set created
     *
     * @param \DateTime $created
     * @return Parametre
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
     * @return Parametre
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
     * Set seo
     *
     * @param \Kreatys\CmsBundle\Entity\Seo $seo
     * @return Parametre
     */
    public function setSeo(\Kreatys\CmsBundle\Entity\Seo $seo = null)
    {
        $this->seo = $seo;

        return $this;
    }

    /**
     * Get seo
     *
     * @return \Kreatys\CmsBundle\Entity\Seo 
     */
    public function getSeo()
    {
        return $this->seo;
    }

    /**
     * Set logo
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $logo
     * @return Parametre
     */
    public function setLogo(\Application\Sonata\MediaBundle\Entity\Media $logo = null)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set logoFooter
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $logoFooter
     * @return Parametre
     */
    public function setLogoFooter(\Application\Sonata\MediaBundle\Entity\Media $logoFooter = null)
    {
        $this->logo_footer = $logoFooter;

        return $this;
    }

    /**
     * Get logoFooter
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getLogoFooter()
    {
        return $this->logo_footer;
    }

    /**
     * Set autres
     *
     * @param array $autres
     * @return Parametre
     */
    public function setAutres($autres)
    {
        $this->autres = $autres;

        return $this;
    }

    /**
     * Get autres
     *
     * @return array 
     */
    public function getAutres()
    {
        return $this->autres;
    }

    /**
     * Set theme_color
     *
     * @param string $themeColor
     * @return Parametre
     */
    public function setThemeColor($themeColor)
    {
        $this->theme_color = $themeColor;

        return $this;
    }

    /**
     * Get theme_color
     *
     * @return string 
     */
    public function getThemeColor()
    {
        return $this->theme_color;
    }
    
    public function getOthers() {
        foreach($this->getAutres() as $param) {
            $this->others[$param['key']] = $param['value'];
        }
        return $this->others;
    }
}
