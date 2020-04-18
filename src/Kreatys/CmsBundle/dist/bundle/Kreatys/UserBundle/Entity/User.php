<?php

namespace Kreatys\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user")
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
     * @ORM\JoinTable(name="users_groups")
     */
    protected $groups;

    /**
     * @ORM\OneToOne(targetEntity="Profil", inversedBy="user", cascade={"persist", "remove"})
     */
    protected $profil;

    public function __construct() {
        parent::__construct();
        $this->groups = new ArrayCollection();
        $this->profil = new Profil();
    }

    /**
     * Set profil
     *
     * @param \Kreatys\UserBundle\Entity\Profil $profil
     * @return User
     */
    public function setProfil(\Kreatys\UserBundle\Entity\Profil $profil = null)
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * Get profil
     *
     * @return \Kreatys\UserBundle\Entity\Profil 
     */
    public function getProfil()
    {
        return $this->profil;
    }

    public function getRole() {
        $role = '';
        if (!empty($this->roles)) {
            $role = $this->roles[0];
        }
        return $role;
    }

    public function setRole($role) {
        $this->setRoles(array($role));
    }
}
