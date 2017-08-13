<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 21.10.15
 * Time: 14:46
 */

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="CoreBundle\Repositories\CityRepository")
 * @ORM\Table(name="city", indexes={
 *      @ORM\Index(name="slug", columns={"slug"})})
 */
class City
{
    use ITDTrait, ImageTrait, GallereyTrait, BackgroundTrait;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("visible")
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $visible;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("region")
     *
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumn(name="region", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $region;

    /**
     * @Gedmo\Slug(fields={"title"})
     *
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("slug")
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $slug;

    /**
     * @JMS\Expose
     * @JMS\SerializedName("organizes")
     * @JMS\Type("CoreBundle\Entity\Organize")
     *
     * @ORM\OneToMany(targetEntity="Organize", mappedBy="city", cascade={"persist", "remove"})
     *
     */
    private $organize;

    /**
     * City constructor.
     */
    public function __construct()
    {
        $this->organize = new ArrayCollection();
        $this->visible = true;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return get_object_vars($this);
    }

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param mixed $visible
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
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param $region
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return ArrayCollection
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

    /**
     * @param $organize
     * @return $this
     */
    public function addOrganize($organize)
    {
        $this->organize[] = $organize;

        return $this;
    }

    /**
     * @param Organize $organize
     * @return $this
     */
    public function removeOrganize(Organize $organize)
    {
        $this->organize->removeElement($organize);

        return $this;
    }
}