<?php

namespace Politeia\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="p_sondage_vote")
 * @ORM\HasLifecycleCallbacks()
 */
class SondageVote
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var SondageChoix 
     * @ORM\ManyToOne(targetEntity="SondageChoix", inversedBy="votes")
     */     
    protected $choix;
    
    /**
     * @var Citoyen 
     * @ORM\ManyToOne(targetEntity="Citoyen", inversedBy="sondageVotes")
     */     
    protected $citoyen;
    
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
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
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
     * Set created
     *
     * @param \DateTime $created
     * @return SondageVote
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
     * @return SondageVote
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
     * Set choix
     *
     * @param \Politeia\CoreBundle\Entity\SondageChoix $choix
     * @return SondageVote
     */
    public function setChoix(\Politeia\CoreBundle\Entity\SondageChoix $choix = null)
    {
        $this->choix = $choix;

        return $this;
    }

    /**
     * Get choix
     *
     * @return \Politeia\CoreBundle\Entity\SondageChoix 
     */
    public function getChoix()
    {
        return $this->choix;
    }

    /**
     * Set citoyen
     *
     * @param \Politeia\CoreBundle\Entity\Citoyen $citoyen
     * @return SondageVote
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
