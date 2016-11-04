<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Sonata\UserBundle\Entity\BaseUser;

/**
 * Class User
 * @package Application\Sonata\UserBundle\Entity
 */
class User extends BaseUser
{
    /**
     * @var int $id
     */
    protected $id;

    /**
     * @var ArrayCollection
     */
    protected $organizes;

    /**
     * @var ArrayCollection
     */
    protected $admin;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->organizes = new ArrayCollection();
        $this->admin     = new ArrayCollection();

    }

    /**
     * Get id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getOrganizes()
    {
        return $this->organizes;
    }

    /**
     * @param $organizes
     * @return $this
     */
    public function setOrganizes($organizes)
    {
        $this->organizes = $organizes;

        return $this;
    }

    /**
     * @param $organize
     * @return $this
     */
    public function addOrganize($organize)
    {
        $this->organizes[] = $organize;

        return $this;
    }

    /**
     * @param $organize
     * @return $this
     */
    public function removeOrganize($organize)
    {
        $this->organizes->removeElement($organize);

        return $this;
    }


    /**
     * @return ArrayCollection
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * @param $admin
     * @return $this
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * @param $admin
     * @return $this
     */
    public function addAdmin($admin)
    {
        $this->admin[] = $admin;

        return $this;
    }

    /**
     * @param $admin
     * @return $this
     */
    public function removeAdmin($admin)
    {
        $this->admin->removeElement($admin);

        return $this;
    }
}
