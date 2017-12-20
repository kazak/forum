<?php
/**
 * Created by PhpStorm.
 * User: Dsolodukhin
 * Date: 19.12.2017
 * Time: 16:00
 */

namespace Forum\Bundle\ForumBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="forum")
 */
class Forum
{
    use \AppBundle\Entity\ITDTrait;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("visible")
     *
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $visible;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("organize")
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Organize")
     * @ORM\JoinColumn(name="organize", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $organize;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("city")
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\City")
     * @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=true)
     */
    protected $city;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("region")
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Region")
     * @ORM\JoinColumn(name="region", referencedColumnName="id", nullable=true)
     */
    protected $region;

    /**
     * @ORM\OneToOne(targetEntity="Voting", mappedBy="id", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="voting", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $voting;

    /**
     * @JMS\Expose
     * @JMS\SerializedName("posts")
     * @JMS\Type("ForumPost")
     *
     * @ORM\OneToMany(targetEntity="ForumPost", mappedBy="forum", cascade={"all"}, orphanRemoval=true)
     */
    protected $posts;

    /**
     * Organize constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
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
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

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
    public function getVoting()
    {
        return $this->voting;
    }

    /**
     * @param $voting
     * @return $this
     */
    public function setVoting($voting)
    {
        $this->voting = $voting;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param $posts
     * @return $this
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * @param $posts
     * @return $this
     */
    public function addPosts($posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * @param $posts
     * @return $this
     */
    public function removePosts(ForumPost $posts)
    {
        $this->posts->removeElement($posts);

        return $this;
    }
}