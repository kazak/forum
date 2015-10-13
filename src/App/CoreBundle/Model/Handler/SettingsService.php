<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 03 06 2015
 */
namespace App\CoreBundle\Model\Handler;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Class SettingsService.
 */
class SettingsService
{
    protected $handler;

    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->handler = $this->container->get('app_core.settings.handler');
    }

    /**
     * @param $code
     *
     * @return mixed
     */
    public function getParams($code = null)
    {
        return json_decode(stream_get_contents($this->handler->getParamsByCode($code), -1, 0));
    }
}
