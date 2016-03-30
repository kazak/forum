<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 Norse Digital.
 * @date: 13 05 2015
 */
namespace CoreBundle\DataFixtures\ORM\Customer;

use Application\Sonata\UserBundle\Entity\User;
use CoreBundle\DataFixtures\ORM\AbstractDollyFixture;
use CoreBundle\DataFixtures\ORM\AbstractForumFixture;

/**
 * Class UsersFixtures.
 */
class UsersFixtures extends AbstractForumFixture
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 0;
    }

    /**
     * @param array $data
     * @return User
     */
    protected function createEntity($data)
    {
        /** @var User $user */
        $user = $this->container->get('fos_user.user_manager')->createUser();

        $user->setUsername($data['userName'])
            ->setFirstname($data['firstName'])
            ->setLastname($data['lastName'])
            ->setEmail($data['email'])
            ->setRoles($data['roles'])
            ->setEnabled(true);

        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $encodedPass = $encoder->encodePassword($data['password'], $user->getSalt());

        $user->setPassword($encodedPass);

        return $user;
    }
}
