<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 30.03.16
 * Time: 11:17
 */

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="partner")
 */
class Partner
{
    use ITDTrait, ImageTrait;

}