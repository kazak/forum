<?php

namespace CoreBundle\Entity;

/**
 * Created by PhpStorm.
 * User: dss
 * Date: 21.10.15
 * Time: 12:59
 */

use CoreBundle\Model\Entity\ContentEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\BlogRepository")
 * @ORM\Table(name="blog_page")
 */
class Blog extends ContentEntity
{

}