<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 21.10.15
 * Time: 12:59
 */

namespace CoreBundle\Entity;

use JMS\Serializer\Annotation as JMS;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Rules
 *
 * @ORM\Table(name="rules")
 * @ORM\Entity
 */
class Rules
{
    use ITDTrait;

}