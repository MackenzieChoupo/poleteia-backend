<?php

namespace Politeia\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="p_abonnement_mairie")
 * @ORM\HasLifecycleCallbacks()
 */
class AbonnmentMairie
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
     * @ORM\ManyToOne(targetEntity="Citoyen", inversedBy="abonnementMairies")
     */
    protected $citoyen;
    
    /**
     * @var Mairie 
     * @ORM\ManyToOne(targetEntity="Mairie", inversedBy="citoyenAbonnes")
     */
    protected $mairie;
    
    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $principale;
    
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set principale
     *
     * @param boolean $principale
     * @return AbonnmentMairie
     */
    public function setPrincipale($principale)
    {
        $this->principale = $principale;

        return $this;
    }

    /**
     * Get principale
     *
     * @return boolean 
     */
    public function getPrincipale()
    {
        return $this->principale;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return AbonnmentMairie
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
     * @return AbonnmentMairie
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
     * @return AbonnmentMairie
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
     * @return AbonnmentMairie
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
