<?php

namespace Politeia\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="p_sondage_choix")
 * @ORM\HasLifecycleCallbacks()
 */
class SondageChoix
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Sondage 
     * @ORM\ManyToOne(targetEntity="Sondage", inversedBy="choix")
     * @Gedmo\SortableGroup
     */
    protected $sondage;
    
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $texte;
    
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Gedmo\SortablePosition
     */
    protected $position;  
    
    /**
     * @var ArrayCollection 
     * @ORM\OneToMany(targetEntity="SondageVote", mappedBy="choix", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $votes;
    
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
     * @return integer
     */
    public function getNbVote()
    {        
        return $this->votes->count();
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
     * @return SondageChoix
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
     * Set position
     *
     * @param integer $position
     * @return SondageChoix
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
     * Set created
     *
     * @param \DateTime $created
     * @return SondageChoix
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
     * @return SondageChoix
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
     * Set sondage
     *
     * @param \Politeia\CoreBundle\Entity\Sondage $sondage
     * @return SondageChoix
     */
    public function setSondage(\Politeia\CoreBundle\Entity\Sondage $sondage = null)
    {
        $this->sondage = $sondage;

        return $this;
    }

    /**
     * Get sondage
     *
     * @return \Politeia\CoreBundle\Entity\Sondage 
     */
    public function getSondage()
    {
        return $this->sondage;
    }

    /**
     * Add votes
     *
     * @param \Politeia\CoreBundle\Entity\SondageVote $votes
     * @return SondageChoix
     */
    public function addVote(\Politeia\CoreBundle\Entity\SondageVote $votes)
    {
        $this->votes[] = $votes;

        return $this;
    }

    /**
     * Remove votes
     *
     * @param \Politeia\CoreBundle\Entity\SondageVote $votes
     */
    public function removeVote(\Politeia\CoreBundle\Entity\SondageVote $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }
}
