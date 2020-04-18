<?php

namespace Politeia\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="p_publicite")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Publicite
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $lien;
    
    /**
     * @var ArrayCollection 
     * @ORM\ManyToMany(targetEntity="Mairie", inversedBy="publicites")
     * @ORM\JoinTable(name="p_publicites_mairies")
     */
    protected $mairies;
    
    /**
     * @var File
     * @Vich\UploadableField(mapping="publicite", fileNameProperty="imageName")
     */
    protected $imageFile;
    
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $imageName;
    
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $datePublicationDebut;
    
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $datePublicationFin;    
    
    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    protected $online;
    
    /**
     * @var int
     * @ORM\Column(type="integer", options={"default"=0})
     */
    protected $nbViews;
    
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastViewed;
    
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
        $this->online = false;
        $this->mairies = new ArrayCollection();
        $this->nbViews = 0;
        $this->lastViewed = new \DateTime();
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
        return $this->id ? 'Publicite' : 'Nouvelle publicite';
    }
    
    /**
     * Set imageFile
     * 
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     * @return Publicite
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated = new \DateTimeImmutable();
        }
        
        return $this;
    }
 
    /**
     * Get imageFile
     *
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
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
     * Set lien
     *
     * @param string $lien
     * @return Publicite
     */
    public function setLien($lien)
    {
        $this->lien = $lien;

        return $this;
    }

    /**
     * Get lien
     *
     * @return string 
     */
    public function getLien()
    {
        return $this->lien;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     * @return Publicite
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string 
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set datePublicationDebut
     *
     * @param \DateTime $datePublicationDebut
     * @return Publicite
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
     * @return Publicite
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
     * Set online
     *
     * @param boolean $online
     * @return Publicite
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
     * Set nbViews
     *
     * @param integer $nbViews
     * @return Publicite
     */
    public function setNbViews($nbViews)
    {
        $this->nbViews = $nbViews;

        return $this;
    }

    /**
     * Get nbViews
     *
     * @return integer 
     */
    public function getNbViews()
    {
        return $this->nbViews;
    }

    /**
     * Set lastViewed
     *
     * @param \DateTime $lastViewed
     * @return Publicite
     */
    public function setLastViewed($lastViewed)
    {
        $this->lastViewed = $lastViewed;

        return $this;
    }

    /**
     * Get lastViewed
     *
     * @return \DateTime 
     */
    public function getLastViewed()
    {
        return $this->lastViewed;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Publicite
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
     * @return Publicite
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
     * Add mairies
     *
     * @param \Politeia\CoreBundle\Entity\Mairie $mairies
     * @return Publicite
     */
    public function addMairy(\Politeia\CoreBundle\Entity\Mairie $mairies)
    {
        $this->mairies[] = $mairies;

        return $this;
    }

    /**
     * Remove mairies
     *
     * @param \Politeia\CoreBundle\Entity\Mairie $mairies
     */
    public function removeMairy(\Politeia\CoreBundle\Entity\Mairie $mairies)
    {
        $this->mairies->removeElement($mairies);
    }

    /**
     * Get mairies
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMairies()
    {
        return $this->mairies;
    }
}
