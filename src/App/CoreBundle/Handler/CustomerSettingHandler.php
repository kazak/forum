<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 22 06 2015
 */
namespace App\CoreBundle\Handler;

use App\CoreBundle\Entity\CustomerSetting;
use App\CoreBundle\Model\Handler\EntityHandler;

/**
 * Class CustomerSettingHandler.
 */
class CustomerSettingHandler extends EntityHandler
{
    /**
     * @param $id
     *
     * @return null|CustomerSetting
     */
    public function getEntity($id)
    {
        return parent::getEntity($id);
    }

    /**
     * @return CustomerSetting
     */
    public function createEntity()
    {
        return parent::createEntity();
    }
}
