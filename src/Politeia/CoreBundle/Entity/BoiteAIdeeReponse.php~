<?php

namespace Politeia\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="p_bai_reponse")
 * @ORM\HasLifecycleCallbacks()
 */
class BoiteAIdeeReponse
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var BoiteAIdeeQuestion
     * @ORM\ManyToOne(targetEntity="BoiteAIdeeQuestion", inversedBy="reponses")
     */
    protected $question; 
    
    /**
     * @var Citoyen 
     * @ORM\ManyToOne(targetEntity="Citoyen", inversedBy="boiteAIdeeReponses")
     */     
    protected $citoyen;
    
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $texte;    
    
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
        return 'Réponse #'.$this->id;
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set texte
     *
     * @param string $texte
     * @return BoiteAIdeeReponse
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
     * Set created
     *
     * @param \DateTime $created
     * @return BoiteAIdeeReponse
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
     * @return BoiteAIdeeReponse
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
     * Set question
     *
     * @param \Politeia\CoreBundle\Entity\BoiteAIdeeQuestion $question
     * @return BoiteAIdeeReponse
     */
    public function setQuestion(\Politeia\CoreBundle\Entity\BoiteAIdeeQuestion $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \Politeia\CoreBundle\Entity\BoiteAIdeeQuestion 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set citoyen
     *
     * @param \Politeia\CoreBundle\Entity\Citoyen $citoyen
     * @return BoiteAIdeeReponse
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
}
