<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 07.06.16
 * Time: 22:50
 */

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait GallereyTrait
 * @package CoreBundle\Entity
 */
trait GallereyTrait
{
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
}