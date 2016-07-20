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
 * @ORM\Table(name="comment")
 */
class Comment
{
    use ITDTrait;

}