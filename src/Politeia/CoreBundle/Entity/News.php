<?php

namespace Politeia\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Politeia\CoreBundle\Repository\NewsRepository")
 * @ORM\Table(name="p_news")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class News
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
     * @ORM\ManyToOne(targetEntity="Mairie", inversedBy="news")
     */     
    protected $mairie;
    
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $titre;
    
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $texte;
    
    /**
     * @var File
     * @Vich\UploadableField(mapping="news", fileNameProperty="photoName")
     */
    protected $photoFile;
    
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $photoName;
    
    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    protected $important;
    
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
     * @var boolean
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    protected $online;
    
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
        $this->online = false;
        $this->important = false;
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
        return (int)$this->id > 0 ? $this->titre : 'Nouvelle actualité';
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
     * @return News
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
     * Set texte
     *
     * @param string $texte
     * @return News
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
     * @return News
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
     * Set important
     *
     * @param boolean $important
     * @return News
     */
    public function setImportant($important)
    {
        $this->important = $important;

        return $this;
    }

    /**
     * Get important
     *
     * @return boolean 
     */
    public function getImportant()
    {
        return $this->important;
    }

    /**
     * Set datePublicationDebut
     *
     * @param \DateTime $datePublicationDebut
     * @return News
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
     * @return News
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
     * @return News
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
     * @return News
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
     * @return News
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
     * @return News
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
}
