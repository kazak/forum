<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 08.06.16
 * Time: 17:48
 */

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CoreBundle\Repositories\VotingRepository", )
 * @ORM\Table(name="voting")
 */
class Voting
{
    use ITDTrait;

    /**
     * @JMS\Expose
     * @JMS\SerializedName("params")
     * @JMS\Type("CoreBundle\Entity\VotingParams")
     *
     * @ORM\OneToMany(targetEntity="VotingParams", mappedBy="voting", cascade={"all"}, orphanRemoval=true)
     */
    protected $params;

    /**
     * @ORM\OneToOne(targetEntity="Forum", mappedBy="voting")
     * @ORM\JoinColumn(name="forum", referencedColumnName="id", nullable=true)
     */
    protected $forum;

    /**
     * @var integer
     */
    protected $allvotings;

    /**
     * Voting constructor.
     */
    public function __construct()
    {
        $this->params = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * @param $forum
     * @return $this
     */
    public function setForum($forum)
    {
        $this->forum = $forum;

        return $this;
    }

    /**
     * @param $param
     * @return $this
     */
    public function addParam($param)
    {
        $this->params[] = $param;

        return $this;
    }

    /**
     * @param ForumPost $param
     * @return $this
     */
    public function removeParam(ForumPost $param)
    {
        $this->params->removeElement($param);

        return $this;
    }

    public function getAllvotings()
    {

    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->title;
    }
}