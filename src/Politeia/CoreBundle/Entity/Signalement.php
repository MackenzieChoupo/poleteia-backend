<?php

namespace Politeia\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Politeia\CoreBundle\Repository\SignalementRepository")
 * @ORM\Table(name="p_signalement")
 * @ORM\HasLifecycleCallbacks()
 */
class Signalement
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var Citoyen 
     * @ORM\ManyToOne(targetEntity="Citoyen", inversedBy="signalements")
     */     
    protected $citoyen;
    
    /**
     * @var Mairie 
     * @ORM\ManyToOne(targetEntity="Mairie", inversedBy="signalements")
     */     
    protected $mairie;
    
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $titre;
    
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $adresse;
    
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $texte;
    
    /**
     * @var File
     */
    protected $photoFile;
    
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $photoName;
    
    /**
     * @var int
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $etat;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=7, nullable=true)
     */
    protected $lat;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=7, nullable=true)
     */
    protected $lng;
    
    const ETAT_SIGNALE = 1;
    const ETAT_VU = 2;
    const ETAT_ENCOURS = 3;
    const ETAT_TRAITE = 4;
    const ETAT_ARCHIVE = 9;
    
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $commentaireMairie;
    
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $commentaireMairieUpdatedAt;
    
    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    protected $online;
    
    /**
     * @var ArrayCollection;
     * @ORM\OneToMany(targetEntity="ConfirmationSignalement", mappedBy="signalement", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $confirmationsSignalements;
    
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    public function __construct()
    {
        $this->setCreated(new \DateTime());
        $this->setUpdated(new \DateTime());   
        $this->confirmationsSignalements = new ArrayCollection();
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
        $this->setUpdated(new \DateTime());
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->titre;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isDeletable() 
    {
        return true;
    }
    
    /**
     * Set photoFile
     * 
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $photo
     * @return News
     */
    public function setPhotoFile(File $photo = null)
    {
        $this->photoFile = $photo;

        if ($photo) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated = new \DateTimeImmutable();
        }
        
        return $this;
    }
 
    /**
     * Get photoFile
     *
     * @return File|null
     */
    public function getPhotoFile()
    {
        return $this->photoFile;
    }
    
    /**
     * 
     * @return int
     */
    public function getNbConfirmation()
    {
        return $this->confirmationsSignalements->count();
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
     * Set titre
     *
     * @param string $titre
     * @return Signalement
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
     * Set adresse
     *
     * @param string $adresse
     * @return Signalement
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set texte
     *
     * @param string $texte
     * @return Signalement
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string 
     */
    public function getTexte()
    {
        return $this->texte;
    }
    
    /**
     * Set photoName
     *
     * @param string $photoName
     * @return Signalement
     */
    public function setPhotoName($photoName)
    {
        $this->photoName = $photoName;

        return $this;
    }

    /**
     * Get photoName
     *
     * @return string 
     */
    public function getPhotoName()
    {
        return $this->photoName;
    }

    /**
     * Set etat
     *
     * @param integer $etat
     * @return Signalement
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return integer 
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set commentaireMairie
     *
     * @param string $commentaireMairie
     * @return Signalement
     */
    public function setCommentaireMairie($commentaireMairie)
    {
        $this->commentaireMairie = $commentaireMairie;

        return $this;
    }

    /**
     * Get commentaireMairie
     *
     * @return string 
     */
    public function getCommentaireMairie()
    {
        return $this->commentaireMairie;
    }

    /**
     * Set commentaireMairieUpdatedAt
     *
     * @param \DateTime $commentaireMairieUpdatedAt
     * @return Signalement
     */
    public function setCommentaireMairieUpdatedAt($commentaireMairieUpdatedAt)
    {
        $this->commentaireMairieUpdatedAt = $commentaireMairieUpdatedAt;

        return $this;
    }

    /**
     * Get commentaireMairieUpdatedAt
     *
     * @return \DateTime 
     */
    public function getCommentaireMairieUpdatedAt()
    {
        return $this->commentaireMairieUpdatedAt;
    }

    /**
     * Set online
     *
     * @param boolean $online
     * @return Signalement
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
     * @return Signalement
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
     * @return Signalement
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
     * Set citoyen
     *
     * @param \Politeia\CoreBundle\Entity\Citoyen $citoyen
     * @return Signalement
     */
    public function setCitoyen(\Politeia\CoreBundle\Entity\Citoyen $citoyen = null)
    {
        $this->citoyen = $citoyen;

        return $this;
    }

    /**
     * Get citoyen
     *
     * @return \Politeia\CoreBundle\Entity\Citoyen 
     */
    public function getCitoyen()
    {
        return $this->citoyen;
    }

    /**
     * Set mairie
     *
     * @param \Politeia\CoreBundle\Entity\Mairie $mairie
     * @return Signalement
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
     * Add confirmationsSignalements
     *
     * @param \Politeia\CoreBundle\Entity\ConfirmationSignalement $confirmationsSignalements
     * @return Signalement
     */
    public function addConfirmationsSignalement(\Politeia\CoreBundle\Entity\ConfirmationSignalement $confirmationsSignalements)
    {
        $this->confirmationsSignalements[] = $confirmationsSignalements;

        return $this;
    }

    /**
     * Remove confirmationsSignalements
     *
     * @param \Politeia\CoreBundle\Entity\ConfirmationSignalement $confirmationsSignalements
     */
    public function removeConfirmationsSignalement(\Politeia\CoreBundle\Entity\ConfirmationSignalement $confirmationsSignalements)
    {
        $this->confirmationsSignalements->removeElement($confirmationsSignalements);
    }

    /**
     * Get confirmationsSignalements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConfirmationsSignalements()
    {
        return $this->confirmationsSignalements;
    }
    
    /**
     * Set lat
     *
     * @param double $lat
     * @return Signalement
     */
    public function setLat($lat) {
        $this->lat = $lat;
        
        return $this;
    }
    
    /**
     * Set lng
     *
     * @param double $lng
     * @return Signalement
     */
    public function setLng($lng) {
        $this->lng = $lng;
        
        return $this;
    }
    
    /**
     * Get lat
     *
     * @return double
     */
    public function getLat() {
        return $this->lat;
    }
    
    /**
     * Get lng
     *
     * @return double
     */
    public function getLng() {
        return $this->lng;
    }
}
