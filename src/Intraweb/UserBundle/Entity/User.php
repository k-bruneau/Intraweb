<?php

namespace Intraweb\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
    * @ORM\ManyToOne(targetEntity="Intraweb\CompanyBundle\Entity\Company", inversedBy="users")
    * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
    */

    private $company;

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
     * Set company_id
     *
     * @param integer $Company
     * @return User
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company_id
     *
     * @return integer 
     */
    public function getCompany()
    {
        return $this->company;
    }
}
