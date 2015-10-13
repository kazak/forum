<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 29 05 2015
 */
namespace App\CoreBundle\Model;

use App\CoreBundle\Entity\Setting;
use App\CoreBundle\Model\Handler\EntityHandler;

/**
 * Class SettingsHandler
 * @package App\CoreBundle\Model
 */
class SettingsHandler extends EntityHandler
{
    /**
     * @param $code
     *
     * @return mixed
     */
    public function getParamsByCode($code)
    {
        $params = [
            'code' => $code,
        ];

        $entities = $this->getEntities($params);

        if (is_null($entities) || empty($entities)) {
            return [];
        }

        /** @var Setting $entity */
        $entity = $entities[0];

        return $entity->getData();
    }
}
