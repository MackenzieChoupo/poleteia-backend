<?php
namespace Kreatys\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Seo
 *
 * @author remi
 */
/**
 * @ORM\Entity
 * @ORM\Table(name="kcms_seo")
 * @ORM\HasLifecycleCallbacks()
 */
class Seo {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Parametre", inversedBy="seo")
     */
    protected $parametre;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $meta_title;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $meta_keywords;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $meta_description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $google_analitics;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $footer;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set meta_title
     *
     * @param string $metaTitle
     * @return Seo
     */
    public function setMetaTitle($metaTitle)
    {
        $this->meta_title = $metaTitle;

        return $this;
    }

    /**
     * Get meta_title
     *
     * @return string 
     */
    public function getMetaTitle()
    {
        return $this->meta_title;
    }

    /**
     * Set meta_keywords
     *
     * @param string $metaKeywords
     * @return Seo
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->meta_keywords = $metaKeywords;

        return $this;
    }

    /**
     * Get meta_keywords
     *
     * @return string 
     */
    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    /**
     * Set meta_description
     *
     * @param string $metaDescription
     * @return Seo
     */
    public function setMetaDescription($metaDescription)
    {
        $this->meta_description = $metaDescription;

        return $this;
    }

    /**
     * Get meta_description
     *
     * @return string 
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * Set footer
     *
     * @param string $footer
     * @return Seo
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * Get footer
     *
     * @return string 
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Seo
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
     * @return Seo
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
     * Set parametre
     *
     * @param \Kreatys\CmsBundle\Entity\Parametre $parametre
     * @return Seo
     */
    public function setParametre(\Kreatys\CmsBundle\Entity\Parametre $parametre = null)
    {
        $this->parametre = $parametre;

        return $this;
    }

    /**
     * Get parametre
     *
     * @return \Kreatys\CmsBundle\Entity\Parametre 
     */
    public function getParametre()
    {
        return $this->parametre;
    }

    /**
     * Set google_analitics
     *
     * @param string $googleAnalitics
     * @return Seo
     */
    public function setGoogleAnalitics($googleAnalitics)
    {
        $this->google_analitics = $googleAnalitics;

        return $this;
    }

    /**
     * Get google_analitics
     *
     * @return string 
     */
    public function getGoogleAnalitics()
    {
        return $this->google_analitics;
    }
}
