<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 01.06.16
 * Time: 14:45
 */

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\Organize;

/**
 * Class OrganizeFixture
 * @package CoreBundle\DataFixtures\ORM
 */
class OrganizeFixtures extends AbstractForumFixture
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 7;
    }

    /**
     * @param array $data
     * @return Organize
     */
    protected function createEntity($data)
    {
        /** @var Organize $organize */
        $organize = $this->container->get('organize.handler')->createEntity();

        $organize->setTitle($data['title'])
            ->setDescription($data['description']);

        return $organize;
    }
}