<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 16.12.15
 * Time: 13:55
 */

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="forum")
 */
class Forum
{
    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("id")
     *
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/\D/",
     *     match=false,
     *     message="ID should be a number"
     * )
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("description")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("visible")
     *
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $visible;

    /**
     * @ORM\ManyToOne(targetEntity="Organize")
     * @ORM\JoinColumn(name="organize", referencedColumnName="id", nullable=true)
     */
    protected $organize;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
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
    public function getOrganize()
    {
        return $this->organize;
    }

    /**
     * @param $organize
     * @return $this
     */
    public function setOrganize($organize)
    {
        $this->organize = $organize;

        return $this;
    }

}