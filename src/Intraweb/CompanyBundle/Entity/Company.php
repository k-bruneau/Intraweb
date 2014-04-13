<?php

namespace Intraweb\CompanyBundle\Entity;

use FOS\UserBundle\Model\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Company
 *
 * @ORM\Table(name="company", indexes={@ORM\Index(name="owner", columns={"owner"})})
 * @ORM\Entity
 */
class Company
{
    public function __construct()
    {
        $this->company = null;
        $this->users = new ArrayCollection();
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var \FosUser
     *
     * @ORM\ManyToOne(targetEntity="Intraweb\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner", referencedColumnName="id")
     * })
     */
    private $owner;

    /**
    * @ORM\OneToMany(targetEntity="Intraweb\UserBundle\Entity\User" , mappedBy="company")
    */

    private $users;
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setOwner(User $owner) {
        $this->owner = $owner;
    }
}
