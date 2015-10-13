<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 14 07 2015
 */
namespace App\CoreBundle\Model\Entity;

use Sylius\Component\Order\Model\AdjustmentInterface as SyliusAdjustmentInterface;

/**
 * Interface AdjustmentInterface
 * @package App\CoreBundle\Model\Entity
 */
interface AdjustmentInterface extends SyliusAdjustmentInterface
{
    /**
     * @return array
     */
    public function getContext();

    /**
     * @param array $context
     *
     * @return AdjustmentInterface
     */
    public function setContext(array $context);
}
