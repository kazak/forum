<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 30.03.16
 * Time: 11:44
 */

namespace CoreBundle\Entity;


/**
 * Trait ImageTrait
 * @package CoreBundle\Entity
 */
trait ImageTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $originalImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
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

    /**
     * @return string
     */
    public function getUploadRootDir()
    {
        // absolute path to your directory where images must be saved
        return __DIR__.'/../web/'.$this->getUploadDir();
    }

    /**
     * @return string
     */
    public function getUploadDir()
    {
        return 'uploads';
    }

    /**
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->image ? null : $this->getUploadRootDir().'/'.$this->image;
    }

    /**
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->image ? null : '/'.$this->getUploadDir().'/'.$this->image;
    }

    /**
     * @return mixed
     */
    public function getOriginalImage()
    {
        return $this->originalImage;
    }

    /**
     * @param mixed $originalImage
     */
    public function setOriginalImage($originalImage)
    {
        $this->originalImage = $originalImage;
    }

}