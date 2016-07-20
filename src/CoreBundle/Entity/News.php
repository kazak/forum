<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 28.03.16
 * Time: 13:13
 */

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity()
 * @ORM\Table(name="news")
 */
class News
{
    use ITDTrait, ImageTrait;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("startPage")
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $startPage;

    /**
     * @Assert\Type("\DateTime")
     * @JMS\Expose
     * @JMS\SerializedName("created")
     * @JMS\Type("string")
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @return mixed
     */
    public function getStartPage()
    {
        return $this->startPage;
    }

    /**
     * @param $startPage
     * @return $this
     */
    public function setStartPage($startPage)
    {
        $this->startPage = $startPage;

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


}