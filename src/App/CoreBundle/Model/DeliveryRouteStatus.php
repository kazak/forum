<?php

/**
 * Calculation of the delivery route status is done in this class.
 */
namespace App\CoreBundle\Model;

use App\CoreBundle\Entity\DeliveryRoute;

/**
 * Class DeliveryRouteStatus
 * @package App\CoreBundle\Model
 */
class DeliveryRouteStatus
{
    /**
     * @var DeliveryRoute
     */
    private $deliveryRoute;

    /**
     * @param DeliveryRoute $deliveryRoute
     * @throws \Exception
     */
    public function __construct(DeliveryRoute &$deliveryRoute)
    {
        if (!$this->isValidDeliveryRoute($deliveryRoute)) {
            throw new \Exception('Invalid delivery route: '.get_class($deliveryRoute));
        }
        $this->deliveryRoute = $deliveryRoute;
    }

    /**
     * Create a new instance of self.
     * 
     * @param DeliveryRoute $deliveryRoute
     *
     * @return DeliveryRouteStatus
     */
    public static function assign(DeliveryRoute &$deliveryRoute)
    {
        return new self($deliveryRoute);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * Get the status value.
     * 
     * @return string
     */
    public function getValue()
    {
        $now = new \DateTime();
        $disabledFrom = $this->deliveryRoute->getDisabledFrom();
        $disabledTo = $this->deliveryRoute->getDisabledTo();

        if (!$this->deliveryRoute->isRouteDefinedInArcGis()) {
            return 'Missing map!';
        }
        if ($this->deliveryRoute->getDeliveryRouteRestaurants()->isEmpty()) {
            return 'No restaurants!';
        }
        if ($disabledFrom < $now && $now < $disabledTo) {
            return 'Disabled';
        }

        return 'OK';
    }

    /**
     * @param $deliveryRoute
     * @return bool
     */
    private function isValidDeliveryRoute($deliveryRoute)
    {
        if (!$deliveryRoute instanceof DeliveryRoute) {
            return false;
        }

        return true;
    }
}
