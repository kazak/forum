<?php

namespace App\CoreBundle\Repository;

use Doctrine\ORM\NoResultException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class CustomerRepository.
 */
class CustomerRepository extends UserRepository
{
    /**
     * @param string $phone
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($phone)
    {
        $query = $this
            ->createQueryBuilder('c')
            ->where('c.phone = :phone')
            ->setParameter('phone', $phone)
            ->getQuery();

        try {
            $user = $query->getSingleResult();
        } catch (NoResultException $e) {
            $message = sprintf(
                    'Unable to find an active App\CoreBundle:Customer object identified by "%s".',
                    $phone
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }

        return $user;
    }

    /**
     * @param string $hotel
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadHotel($hotel)
    {
        $query = $this
            ->createQueryBuilder('c')
            ->leftJoin('c.invoice', 'i')
            ->where('c.customerType = 3')
            ->andWhere('i.password = :password')
            ->setParameter('password', $hotel)
            ->getQuery();

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}
