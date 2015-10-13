<?php

namespace App\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * PostCodeRepository.
 */
class PostCodeRepository extends EntityRepository
{
    /**
     * @param string $query
     *
     * @return mixed
     */
    public function search($query)
    {
        $queryParts = $this->processQuery($query);

        $postCode = false;
        // First try to find post code
        if ($queryParts['postCode']) {
            $postCode = $this->getByCode($queryParts['postCode']);
        }
        // If no post code was specified or found please search for city if specified
        if ($postCode === false && $queryParts['city']) {
            $codes = $this->getByCity($queryParts['city']);
            if (count($codes) > 0) {
                $postCode = $codes[0];
            } else {
                $codes = $this->getByMuniciplaity($queryParts['city']);
                $postCode = count($codes) > 0 ? $codes[0] : false;
            }
        }

        return $postCode;
    }

    /**
     * @param $code
     * @return bool|mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByCode($code)
    {
        $query = $this
            ->createQueryBuilder('pc')
            ->where('pc.postcode = :postcode')
            ->setParameter('postcode', $code)
            ->getQuery();

        try {
            $postCode = $query->getSingleResult();
        } catch (NoResultException $e) {
            $postCode = false;
        }

        return $postCode;
    }

    /**
     * @param $city
     * @return array
     */
    public function getByCity($city)
    {
        $query = $this
            ->createQueryBuilder('pc')
            ->where('pc.city = :city')
            ->setParameter('city', $city)
            ->getQuery();

        try {
            $postCodes = $query->getResult();
        } catch (NoResultException $e) {
            $postCodes = [];
        }

        return $postCodes;
    }

    /**
     * @param $municiplaity
     * @return array
     */
    public function getByMuniciplaity($municiplaity)
    {
        $query = $this
            ->createQueryBuilder('pc')
            ->where('pc.municiplaity = :municiplaity')
            ->setParameter('municiplaity', $municiplaity)
            ->getQuery();

        try {
            $postCodes = $query->getResult();
        } catch (NoResultException $e) {
            $postCodes = [];
        }

        return $postCodes;
    }

    /**
     * @param $query
     * @return array
     */
    private function processQuery($query)
    {
        $result = ['postCode' => false,'city' => false];

        $parts = explode(' ', trim($query));

        foreach ($parts as $part) {
            if (preg_match('/^\d\d\d\d$/', $part)) {
                $result['postCode'] = $part;
            } elseif (strlen($part) > 0) {
                if ($result['city']) {
                    $result['city'] .= ' '.$part;
                } else {
                    $result['city'] = $part;
                }
            }
        }

        return $result;
    }
}
