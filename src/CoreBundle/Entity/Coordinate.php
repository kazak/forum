<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 21.10.15
 * Time: 14:54
 */

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\CoordinateRepository")
 * @ORM\Table(name="coordinate")
 */
class Coordinate
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * */
    protected $id;

    /**
     * @ORM\Column(type="text")
     */
    protected $polygon;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
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
    public function getPolygon()
    {
        return json_decode($this->polygon);
    }

    /**
     * @param mixed $polygon
     * @return $this
     */
    public function setPolygon($polygon)
    {
        $this->polygon = json_encode($polygon);

        return $this;
    }


}