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
    protected $organizeds;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->organizeds = new ArrayCollection();
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
    public function getOrganizeds()
    {
        return $this->organizeds;
    }

    /**
     * @param $organizeds
     * @return $this
     */
    public function setOrganizeds($organizeds)
    {
        $this->organizeds = $organizeds;

        return $this;
    }

    /**
     * @param $organize
     * @return $this
     */
    public function addOrganized($organize)
    {
        $this->organizeds[] = $organize;

        return $this;
    }
}
