<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 05 07 2015
 */
namespace App\CoreBundle\Handler;

use App\CoreBundle\Entity\Seo;
use App\CoreBundle\Model\Handler\EntityHandler;

/**
 * Class SeoHandler.
 */
class SeoHandler extends EntityHandler
{
    /**
     * @param $id
     *
     * @return null|Seo
     */
    public function getEntity($id)
    {
        return parent::getEntity($id);
    }

    /**
     * @return Seo
     */
    public function createEntity()
    {
        return parent::createEntity();
    }
}
