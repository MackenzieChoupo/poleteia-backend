<?php

namespace Politeia\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="p_bai_question")
 * @ORM\HasLifecycleCallbacks()
 */
class BoiteAIdeeQuestion
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var Mairie 
     * @ORM\ManyToOne(targetEntity="Mairie", inversedBy="boiteAIdeeQuestions")
     * @Gedmo\SortableGroup
     */     
    protected $mairie;
    
    /*
     * var BoiteAIdeeTheme
     * ORM\ManyToOne(targetEntity="BoiteAIdeeTheme", inversedBy="questions")
     * Gedmo\SortableGroup     
    protected $theme;*/
    
    /**
     * @var int
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $theme;
    
    const THEME_EDUCATION_JEUNESSE = 1;
    const THEME_SOCIAL = 2;
    const THEME_URBANISME_TRAVAUX = 3;
    const THEME_ANIMATION = 4;    
    
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $titre;
    
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $datePublicationDebut;
    
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $datePublicationFin; 
    
    /**
     * @var ArrayCollection 
     * @ORM\OneToMany(targetEntity="BoiteAIdeeReponse", mappedBy="question", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"updated" = "DESC"})
     */
    protected $reponses;
    
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Gedmo\SortablePosition
     */
    protected $position;
    
    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    protected $online;    
    
    /**
     * @var \Datetime $created
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @var \Datetime $updated
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $updated;

    public function __construct()
    {
        $this->setCreated(new \DateTime());
        $this->setUpdated(new \DateTime());
        $this->reponses = new ArrayCollection();
        $this->online = true;       
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
        $this->setUpdated(new \DateTime());
    }
    
    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->id ? $this->titre : 'Nouvelle question';
    }
    
    /**
     * 
     * @return boolean
     */
    public function isDeletable()
    {
        return $this->reponses->count() === 0;
    }
    
    /**
     * 
     * @return integer
     */
    public function getNbReponse()
    {
        return $this->reponses->count();
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
     * Set theme
     *
     * @param integer $theme
     * @return BoiteAIdeeQuestion
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return integer 
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return BoiteAIdeeQuestion
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set datePublicationDebut
     *
     * @param \DateTime $datePublicationDebut
     * @return BoiteAIdeeQuestion
     */
    public function setDatePublicationDebut($datePublicationDebut)
    {
        $this->datePublicationDebut = $datePublicationDebut;

        return $this;
    }

    /**
     * Get datePublicationDebut
     *
     * @return \DateTime 
     */
    public function getDatePublicationDebut()
    {
        return $this->datePublicationDebut;
    }

    /**
     * Set datePublicationFin
     *
     * @param \DateTime $datePublicationFin
     * @return BoiteAIdeeQuestion
     */
    public function setDatePublicationFin($datePublicationFin)
    {
        $this->datePublicationFin = $datePublicationFin;

        return $this;
    }

    /**
     * Get datePublicationFin
     *
     * @return \DateTime 
     */
    public function getDatePublicationFin()
    {
        return $this->datePublicationFin;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return BoiteAIdeeQuestion
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set online
     *
     * @param boolean $online
     * @return BoiteAIdeeQuestion
     */
    public function setOnline($online)
    {
        $this->online = $online;

        return $this;
    }

    /**
     * Get online
     *
     * @return boolean 
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return BoiteAIdeeQuestion
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
     * @return BoiteAIdeeQuestion
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
     * Set mairie
     *
     * @param \Politeia\CoreBundle\Entity\Mairie $mairie
     * @return BoiteAIdeeQuestion
     */
    public function setMairie(\Politeia\CoreBundle\Entity\Mairie $mairie = null)
    {
        $this->mairie = $mairie;

        return $this;
    }

    /**
     * Get mairie
     *
     * @return \Politeia\CoreBundle\Entity\Mairie 
     */
    public function getMairie()
    {
        return $this->mairie;
    }

    /**
     * Add reponses
     *
     * @param \Politeia\CoreBundle\Entity\BoiteAIdeeReponse $reponses
     * @return BoiteAIdeeQuestion
     */
    public function addReponse(\Politeia\CoreBundle\Entity\BoiteAIdeeReponse $reponses)
    {
        $this->reponses[] = $reponses;

        return $this;
    }

    /**
     * Remove reponses
     *
     * @param \Politeia\CoreBundle\Entity\BoiteAIdeeReponse $reponses
     */
    public function removeReponse(\Politeia\CoreBundle\Entity\BoiteAIdeeReponse $reponses)
    {
        $this->reponses->removeElement($reponses);
    }

    /**
     * Get reponses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReponses()
    {
        return $this->reponses;
    }
}
