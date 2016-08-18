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
     * @var string $status_name
     */
    protected $status_name;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->organizes = new ArrayCollection();
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
     */
    public function removeOrganize($organize)
    {
        $this->organizes->removeElement($organize);
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        return $this->status_name;
    }

    /**
     * @param $status_name
     * @return $this
     */
    public function setStatusName($status_name)
    {
        $this->status_name = $status_name;

        return $this;
    }

}
