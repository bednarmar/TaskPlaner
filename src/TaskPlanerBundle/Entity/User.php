<?php

namespace TaskPlanerBundle\Entity;


use FOS\UserBundle\Entity\User as BaseUser;
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
     * @ORM\OneToMany(targetEntity="TaskPlanerBundle\Entity\Category", mappedBy="user")
     */
    private $categories;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Add categories
     *
     * @param \TaskPlanerBundle\Entity\Category $categories
     * @return User
     */
    public function addCategory(\TaskPlanerBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \TaskPlanerBundle\Entity\Category $categories
     */
    public function removeCategory(\TaskPlanerBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
