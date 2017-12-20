<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 08.06.16
 * Time: 17:48
 */

namespace Forum\Bundle\ForumBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Forum\Bundle\ForumBundle\Repositories\VotingRepository", )
 * @ORM\Table(name="forum_voting")
 */
class Voting
{
    use \AppBundle\Entity\ITDTrait;

    /**
     * @JMS\Expose
     * @JMS\SerializedName("params")
     * @JMS\Type("VotingParams")
     *
     * @ORM\OneToMany(targetEntity="VotingParams", mappedBy="voting", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $params;

    /**
     * @var integer
     */
    protected $allvotings = 0;

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

    /**
     * @return int
     */
    public function getAllvotings()
    {
        $allParams = $this->getParams();

        /** @var VotingParams $param */
        foreach($allParams as $param){
            $this->allvotings += $param->getRating();
        }

        return $this->allvotings;
    }

}