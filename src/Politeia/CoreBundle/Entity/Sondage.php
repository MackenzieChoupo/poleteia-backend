<?php

namespace Politeia\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Politeia\CoreBundle\Repository\SondageRepository")
 * @ORM\Table(name="p_sondage")
 * @ORM\HasLifecycleCallbacks()
 */
class Sondage
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
     * @ORM\ManyToOne(targetEntity="Mairie", inversedBy="sondages")
     */     
    protected $mairie;
    
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $question;
    
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $questionCible;
    
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
     * @ORM\OneToMany(targetEntity="SondageChoix", mappedBy="sondage", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $choix;
    
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
        $this->choix = new ArrayCollection();
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
        return $this->id ? $this->question : 'Nouveau sondage';
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
     * 
     * @return integer
     */
    public function getNbReponse()
    {
        $nb = 0;
        foreach ($this->choix as $choix) {
            $nb += $choix->getVotes()->count();
        }
        return $nb;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isEnded()
    {
        $now = new \DateTime();
        return $now >= $this->datePublicationFin;
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
     * Set question
     *
     * @param string $question
     * @return Sondage
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set questionCible
     *
     * @param string $questionCible
     * @return Sondage
     */
    public function setQuestionCible($questionCible)
    {
        $this->questionCible = $questionCible;

        return $this;
    }

    /**
     * Get questionCible
     *
     * @return string 
     */
    public function getQuestionCible()
    {
        return $this->questionCible;
    }

    /**
     * Set datePublicationDebut
     *
     * @param \DateTime $datePublicationDebut
     * @return Sondage
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
     * @return Sondage
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
     * @return Sondage
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
     * @return Sondage
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
     * @return Sondage
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
     * @return Sondage
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
     * Add choix
     *
     * @param \Politeia\CoreBundle\Entity\SondageChoix $choix
     * @return Sondage
     */
    public function addChoix(\Politeia\CoreBundle\Entity\SondageChoix $choix)
    {
        $this->choix[] = $choix;
        $choix->setSondage($this);

        return $this;
    }

    /**
     * Remove choix
     *
     * @param \Politeia\CoreBundle\Entity\SondageChoix $choix
     */
    public function removeChoix(\Politeia\CoreBundle\Entity\SondageChoix $choix)
    {
        $this->choix->removeElement($choix);
    }

    /**
     * Get choix
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChoix()
    {
        return $this->choix;
    }
}
