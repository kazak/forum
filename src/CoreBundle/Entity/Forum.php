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
    use ITDTrait;

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