<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 Norse Digital.
 * @date: 13 05 2015
 */
namespace AppBundle\DataFixtures\ORM;

use Application\Sonata\UserBundle\Entity\User;

/**
 * Class UserFixtures.
 */
class UserFixtures extends AbstractFixtures
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
