<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 30.03.16
 * Time: 11:44
 */

namespace CoreBundle\Entity;

use Iphp\FileStoreBundle\Mapping\Annotation as FileStore;

/**
 * Trait ImageTrait
 * @package CoreBundle\Entity
 */
trait ImageTrait
{
    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("image")
     *
     * @Assert\File( maxSize="10M")
     * @FileStore\UploadableField(mapping="photo")
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $image;

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
}