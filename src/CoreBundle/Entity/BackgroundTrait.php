<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 15.06.16
 * Time: 11:32
 */

namespace CoreBundle\Entity;

/**
 * Class BackgroundTrait
 * @package CoreBundle\Entity
 */
Trait BackgroundTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $background;

    /**
     * @return mixed
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * @return string
     */
    public function getPathBackground()
    {
        return  '/uploads/'.$this->background;
    }
    /**
     * @param mixed $background
     * @return $this
     */
    public function setBackground($background)
    {
        $this->background = $background;

        return $this;
    }

}