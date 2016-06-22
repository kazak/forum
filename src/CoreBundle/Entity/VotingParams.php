<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 08.06.16
 * Time: 18:03
 */

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CoreBundle\Repositories\VotingParamsRepository", )
 * @ORM\Table(name="voting_params")
 */
class VotingParams
{
    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("id")
     *
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
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinTable(name="user_voting_param",
     *      joinColumns={@ORM\JoinColumn(name="id_voting_param", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_user", referencedColumnName="id")}
     *      )
     */
    protected $users;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("voting")
     *
     * @ORM\ManyToOne(targetEntity="Voting")
     * @ORM\JoinColumn(name="voting", referencedColumnName="id")
     */
    protected $voting;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("rating")
     */
    protected $rating;

    /**
     * VotingParams constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->rating = sizeof($this->users);
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

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param $users
     * @return $this
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return sizeof($this->users);
    }

    /**
     * @param $rating
     * @return $this
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

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
    public function __toString()
    {
        return $this->title;
    }
}