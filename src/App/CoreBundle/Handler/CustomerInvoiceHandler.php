<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 22 06 2015
 */
namespace App\CoreBundle\Handler;

use App\CoreBundle\Entity\CustomerInvoice;
use App\CoreBundle\Model\Handler\EntityHandler;

/**
 * Class CustomerInvoiceHandler.
 */
class CustomerInvoiceHandler extends EntityHandler
{
    /**
     * @param $id
     *
     * @return null|CustomerInvoice
     */
    public function getEntity($id)
    {
        return parent::getEntity($id);
    }

    /**
     * @return CustomerInvoice
     */
    public function createEntity()
    {
        return parent::createEntity();
    }
}
