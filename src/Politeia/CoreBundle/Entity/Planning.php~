<?php

namespace Politeia\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity()
 * @ORM\Table(name="p_planning")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Planning
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
     * @ORM\ManyToOne(targetEntity="Mairie", inversedBy="plannings")
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
     * @Vich\UploadableField(mapping="planning", fileNameProperty="pdfName")
     * @Assert\File(maxSize = "50M", mimeTypes = {"application/pdf", "application/x-pdf"}, mimeTypesMessage = "Vous devez choisir un fichier PDF valide.")
     */
    protected $pdfFile;
    
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $pdfName;
    
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
        return 'Planning';
    }
    
    /**
     * Set imageFile
     * 
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     * @return Mairie
     */
    public function setPdfFile(File $pdf = null)
    {
        $this->pdfFile = $pdf;

        if ($pdf) {
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
    public function getPdfFile()
    {
        return $this->pdfFile;
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
     * @return Planning
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
     * @return Planning
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
     * Set pdfName
     *
     * @param string $pdfName
     * @return Planning
     */
    public function setPdfName($pdfName)
    {
        $this->pdfName = $pdfName;

        return $this;
    }

    /**
     * Get pdfName
     *
     * @return string 
     */
    public function getPdfName()
    {
        return $this->pdfName;
    }

    /**
     * Set online
     *
     * @param boolean $online
     * @return Planning
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
     * @return Planning
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
     * @return Planning
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
     * @return Planning
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
