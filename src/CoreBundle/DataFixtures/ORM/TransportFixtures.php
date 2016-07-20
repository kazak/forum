<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 Norse Digital.
 * @date: 13 05 2015
 */
namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\Transport;

/**
 * Class TransportFixtures.
 */
class TransportFixtures extends AbstractForumFixture
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }

    /**
     * @param array $data
     * @return Transport
     */
    protected function createEntity($data)
    {
        /** @var Transport $transport */
        $transport = $this->container->get('transport.handler')->createEntity();

        $transport->setTitle($data['title']);

        return $transport;
    }
}
