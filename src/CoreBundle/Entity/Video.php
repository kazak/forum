<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 27.05.16
 * Time: 10:47
 */

namespace CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="CoreBundle\Repositories\VideoRepository", )
 * @ORM\Table(name="video")
 */
class Video
{
    use ITDTrait;
}