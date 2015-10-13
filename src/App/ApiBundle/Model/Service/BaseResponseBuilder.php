<?php
 /**
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 01 09 2015
 */

namespace App\ApiBundle\Model\Service;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class BaseResponseBuilder implements BaseResponseBuilderInterface
{
    use ContainerAwareTrait;

    /**
     * Constructor.
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container);
    }

    public function getResponseBase()
    {
        return [
            'status' => 200,
            'data' => [],
            'error' => []
        ];
    }

    /**
     * @return string
     */
    protected function getOrderObtainmentType()
    {
        $default = 'takeaway';

        //TODO: get obtainment type from session
        $orderHandler = $this->container->get("app_core.order.handler");
        $orderData = $orderHandler->getCurrentOrder();
        $type = isset($orderData['type']) ? $orderData['type'] : null;

        return $type ?: $default;
    }
}
