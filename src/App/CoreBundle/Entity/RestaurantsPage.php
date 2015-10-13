<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 04 06 2015
 */
namespace App\CoreBundle\Entity;

use App\CoreBundle\Model\Entity\ContentEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\CoreBundle\Repository\RestaurantsPageRepository")
 * @ORM\Table(name="restaurants_page")
 */
class RestaurantsPage extends ContentEntity
{
    /**
     * @ORM\Column(type="decimal", precision=9, scale=6, nullable=true, name="map_center_latitude")
     */
    private $mapCenterLatitude;

    /**
     * @ORM\Column(type="decimal", precision=9, scale=6, nullable=true, name="map_center_longitude")
     */
    private $mapCenterLongitude;

    /**
     * @ORM\Column(length=50, name="search_field_placeholder")
     */
    private $searchFieldPlaceholder;

    /**
     * @ORM\Column(length=20, name="search_button_label")
     */
    private $searchButtonLabel;

    /**
     * @return array
     */
    public function getData()
    {
        return get_object_vars($this);
    }

    /**
     * Set mapCenterLatitude.
     *
     * @param string $mapCenterLatitude
     *
     * @return RestaurantsPage
     */
    public function setMapCenterLatitude($mapCenterLatitude)
    {
        $this->mapCenterLatitude = $mapCenterLatitude;

        return $this;
    }

    /**
     * Get mapCenterLatitude.
     *
     * @return string
     */
    public function getMapCenterLatitude()
    {
        return $this->mapCenterLatitude;
    }

    /**
     * Set mapCenterLongitude.
     *
     * @param string $mapCenterLongitude
     *
     * @return RestaurantsPage
     */
    public function setMapCenterLongitude($mapCenterLongitude)
    {
        $this->mapCenterLongitude = $mapCenterLongitude;

        return $this;
    }

    /**
     * Get mapCenterLongitude.
     *
     * @return string
     */
    public function getMapCenterLongitude()
    {
        return $this->mapCenterLongitude;
    }

    /**
     * Set searchFieldPlaceholder.
     *
     * @param string $placeholder
     *
     * @return RestaurantsPage
     */
    public function setSearchFieldPlaceholder($placeholder)
    {
        $this->searchFieldPlaceholder = $placeholder;

        return $this;
    }

    /**
     * Get searchFieldPlaceholder.
     *
     * @return string
     */
    public function getSearchFieldPlaceholder()
    {
        return $this->searchFieldPlaceholder;
    }

    /**
     * Set searchButtonLabel.
     *
     * @param string $searchButtonLabel
     *
     * @return RestaurantsPage
     */
    public function setSearchButtonLabel($searchButtonLabel)
    {
        $this->searchButtonLabel = $searchButtonLabel;

        return $this;
    }

    /**
     * Get searchButtonLabel.
     *
     * @return string
     */
    public function getSearchButtonLabel()
    {
        return $this->searchButtonLabel;
    }
}
