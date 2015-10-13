<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 09.10.15
 */

namespace App\CoreBundle\Model\Serializator;

use JMS\Serializer\SerializerBuilder;

trait JMSSerializable
{
    /**
     * @return string
     */
    public function toJson()
    {
        return SerializerBuilder::create()->build()->serialize($this, 'json');
    }

    /**
     * @return string
     */
    public function toArray()
    {
        return json_decode($this->toJson(), true);
    }
}