<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 16.12.15
 * Time: 14:09
 *
 */

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="forum_post", indexes={
 *      @ORM\Index(name="created", columns={"created"})})
 */
class ForumPost
{
    use ITDTrait;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("visible")
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $visible;

    /**
     * @Assert\Type("\DateTime")
     * @JMS\Expose
     * @JMS\SerializedName("created")
     * @JMS\Type("string")
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $created;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner", referencedColumnName="id")
     */
    protected $owner;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("forum")
     *
     * @ORM\ManyToOne(targetEntity="Forum")
     * @ORM\JoinColumn(name="forum", referencedColumnName="id")
     */
    protected $forum;

    /**
     * ForumPost constructor.
     */
    public function __construct()
    {
        $this->visible = true;
    }

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
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param $created
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param $owner
     * @return $this
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

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
    public function __toString()
    {
        $message = mb_strimwidth($this->getDescription(), 0, 10, '...');
        return '№'.$this->id.' - '.$message." (".$this->owner->getUsername().") ";
    }
}