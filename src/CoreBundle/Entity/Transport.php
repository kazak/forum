<?php
/**
 * Created by muha.
 * User: dss
 * Date: 15.07.16
 * Time: 12:52
 */

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity()
 * @ORM\Table(name="transport")
 */
class Transport
{
    use ITDTrait, ImageTrait, GallereyTrait;
}