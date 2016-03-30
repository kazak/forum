<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 16.12.15
 * Time: 14:05
 */

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="forum_team")
 */
class ForumTeam
{
    use ITDTrait;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("visible")
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $visible;

    /**
     * @ORM\ManyToOne(targetEntity="Forum")
     * @ORM\JoinColumn(name="forum", referencedColumnName="id")
     */
    protected $forum;

    /**
     * @Assert\Type("\DateTime")
     * @JMS\Expose
     * @JMS\SerializedName("updated")
     * @JMS\Type("string")
     *
     * @ORM\Column(type="datetime")
     */
    protected $update;

    /**
     * @Assert\Type("\DateTime")
     * @JMS\Expose
     * @JMS\SerializedName("create")
     * @JMS\Type("string")
     *
     * @ORM\Column(type="datetime")
     */
    protected $create;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="admin", referencedColumnName="id", nullable=true)
     */
    protected $admin;

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param $visible
     * @return $this
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * @param $forum
     * @return $this
     */
    public function setForum($forum)
    {
        $this->forum = $forum;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdate()
    {
        return $this->update;
    }

    /**
     * @param $update
     * @return $this
     */
    public function setUpdate($update)
    {
        $this->update = $update;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreate()
    {
        return $this->create;
    }

    /**
     * @param $create
     * @return $this
     */
    public function setCreate($create)
    {
        $this->create = $create;

        return $this;
    }

    /**
     * @return mixed
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
}