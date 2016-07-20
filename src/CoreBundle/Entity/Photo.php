<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 27.05.16
 * Time: 10:47
 */

namespace CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity()
 * @ORM\Table(name="photo")
 */
class Photo
{
    use ImageTrait;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("id")
     *
     * @Assert\Regex(
     *     pattern="/\D/",
     *     match=false,
     *     message="ID should be a number"
     * )
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * */
    protected $id;


    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("title")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $gallery;

    /**
     * @return mixed
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * @param $gallery
     * @return $this
     */
    public function setGallery($gallery)
    {
        $this->gallery = $gallery;

        return $this;
    }

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }


}