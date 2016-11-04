<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="admin")
 */
class Admin
{
    use ITDTrait;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("owner")
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner", referencedColumnName="id")
     */
    protected $owner;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("organize")
     *
     * @ORM\ManyToOne(targetEntity="Organize")
     * @ORM\JoinColumn(name="organize", referencedColumnName="id")
     */
    protected $organize;

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return mixed
     */
    public function getOrganize()
    {
        return $this->organize;
    }

    /**
     * @param mixed $organize
     */
    public function setOrganize($organize)
    {
        $this->organize = $organize;
    }


}