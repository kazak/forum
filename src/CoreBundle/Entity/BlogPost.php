<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BlogPost
 *
 * @ORM\Table(name="blog_post")
 * @ORM\Entity
 */
class BlogPost
{
    use ITDTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="blogPosts")
     */
    private $category;

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }


}
