<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 29 05 2015
 */
namespace App\CoreBundle\Service;

use App\CoreBundle\Model\Handler\SettingsService;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class WebStateService.
 */
class WebStateService extends SettingsService
{
    const PARAM_KEY = 'web_status';

    /**
     * @return mixed False by default
     */
    public function getStatus()
    {
        $params = $this->getParams(self::PARAM_KEY);

        if (is_object($params)) {
            return $params->state;
        }

        return false;
    }

    /**
     * @return string Empty brackets by default
     */
    public function getMessage()
    {
        $params = $this->getParams(self::PARAM_KEY);

        if (is_object($params)) {
            return $params->message;
        }

        return '[]';
    }

    /**
     * @param string|null $code
     *
     * @return mixed
     */
    public function getParams($code = null)
    {
        return parent::getParams(self::PARAM_KEY);
    }

    /**
     * @param Request $request
     *
     * @return \stdClass
     */
    public function updateStatus(Request $request)
    {
        $data = $request->request->all();
        $status = $this->getStatus();
        $object = new \stdClass();
        $object->message = $data['message'] ?  $data['message'] : $this->getParams()->message;
        $object->state = $data['status'] ? !$status : $status;

        $this->handler->setParamsByCode(self::PARAM_KEY, json_encode($object));

        return $object;
    }
}
