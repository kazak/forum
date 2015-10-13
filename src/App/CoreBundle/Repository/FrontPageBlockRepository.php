<?php

namespace App\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * FrontPageBlockRepository.
 *
 */
class FrontPageBlockRepository extends EntityRepository
{
    /**
     * Get max priority value
     * @param $frontPage
     * @return array
     */
    public function getMaxPriority($frontPage)
    {
        $maxPriority = $this->createQueryBuilder('b')
            ->select('MAX(b.priority)')
            ->where('b.frontPage = :frontPage')
            ->setParameter('frontPage', $frontPage)
            ->getQuery()
            ->getResult();

        return $maxPriority[0][1];
    }
}
