<?php
/**
 * Created by PhpStorm.
 * User: kazak
 * Date: 12/26/15
 * Time: 11:20 PM
 */

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\Partner;

class PartnerFixtures extends AbstractForumFixture
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 5;
    }

    /**
     * @param array $data
     * @return Partner
     */
    protected function createEntity($data)
    {
        /** @var Partner $partner */
        $partner = $this->container->get('partner.handler')->createEntity();

        $partner->setTitle($data['title'])
            ->setDescription($data['description'])
            ->setEmail($data['email'])
            ->setAddress($data['address'])
            ->setBalance($data['balanse'])
            ->setVisible(true)
            ->setImage($data['img'])
            ->setVip($data['vip']);

        return $partner;
    }
} 