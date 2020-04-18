<?php

namespace Politeia\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="p_confirmation_signalement")
 * @ORM\HasLifecycleCallbacks()
 */
class ConfirmationSignalement
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var Signalement 
     * @ORM\ManyToOne(targetEntity="Signalement", inversedBy="confirmationsSignalements")
     */     
    protected $signalement;
    
    /**
     * @var Citoyen 
     * @ORM\ManyToOne(targetEntity="Citoyen", inversedBy="confirmationsSignalements")
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
     * @return ConfirmationSignalement
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
     * @return ConfirmationSignalement
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
     * Set signalement
     *
     * @param \Politeia\CoreBundle\Entity\Signalement $signalement
     * @return ConfirmationSignalement
     */
    public function setSignalement(\Politeia\CoreBundle\Entity\Signalement $signalement = null)
    {
        $this->signalement = $signalement;

        return $this;
    }

    /**
     * Get signalement
     *
     * @return \Politeia\CoreBundle\Entity\Signalement 
     */
    public function getSignalement()
    {
        return $this->signalement;
    }

    /**
     * Set citoyen
     *
     * @param \Politeia\CoreBundle\Entity\Citoyen $citoyen
     * @return ConfirmationSignalement
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
