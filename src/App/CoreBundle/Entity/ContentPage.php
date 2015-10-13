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
 * @ORM\Entity(repositoryClass="App\CoreBundle\Repository\ContentPageRepository")
 * @ORM\Table(name="content_page")
 */
class ContentPage extends ContentEntity
{
    /**
     * @ORM\Column(type="boolean", options={"default":1})
     */
    protected $siteMap;

    /**
     * @return mixed
     */
    public function getSiteMap()
    {
        return $this->siteMap;
    }

    /**
     * @param mixed $siteMap
     * @return $this
     */
    public function setSiteMap($siteMap)
    {
        $this->siteMap = $siteMap;

        return $this;
    }
}
