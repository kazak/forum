<?php
/**
 * @author:     aat <aat@norse.digital>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 02 10 2015
 */
namespace App\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;


class ImageRepository extends EntityRepository
{
    /**
     * Get image count
     * @param null|string $folder
     * @return int
     */
    public function imageCount($folder = null)
    {
        $imageCount = $this->createQueryBuilder('i')
            ->select('COUNT(i.id)');
        $imageCount = $this->bindWhere($folder, $imageCount);
        $imageCount = $imageCount->getQuery()
            ->getResult();

        return (int)$imageCount[0][1];
    }


    /**
     * @param array $folders
     * @param string $dir
     * @return int
     */
    public function imageCountNotInFolder(array $folders = [], $dir = '')
    {
        $imageCount = $this->createQueryBuilder('i')
            ->select('COUNT(i.id)');
        $imageCount = $this->bindWheres($folders, $dir, $imageCount);
        $imageCount = $imageCount->getQuery()->getResult();

        return (int)$imageCount[0][1];
    }

    /**
     * Get image
     * @param null|string $folder
     * @return int
     */
    public function imagesByFolder($folder = null, $limit = null, $offset = null)
    {
        $images = $this->createQueryBuilder('i')
            ->select();
        $images = $this->bindWhere($folder, $images);
        if ($limit !== null) {
            $images = $images->setMaxResults($limit);
        }
        if ($offset !== null) {
            $images = $images->setFirstResult($offset);
        }
        $images = $images->getQuery()
            ->getResult();

        return $images;
    }

    /**
     * @param array $folders
     * @param string $dir
     * @param null|int $limit
     * @param null|int $offset
     * @return array
     */
    public function imageNotInFolder(array $folders = [], $dir = '', $limit = null, $offset = null)
    {
        $images = $this->createQueryBuilder('i')
            ->select();
        $images = $this->bindWheres($folders, $dir, $images);
        if ($limit !== null) {
            $images = $images->setMaxResults($limit);
        }
        if ($offset !== null) {
            $images = $images->setFirstResult($offset);
        }
        $images = $images->getQuery()->getResult();

        return $images;
    }

    /**
     * Get image folders
     * @return array
     */
    public function imageFolders()
    {
        $manager = $this->getEntityManager();
        $connection = $manager->getConnection();
        $query = 'SELECT DISTINCT LEFT(replace(`path`, "/uploads/", ""), INSTR(replace(`path`, "/uploads/", ""), "/") - 1) AS title FROM images';
        $statement = $connection->prepare($query);
        $statement->execute();
        $folders = $statement->fetchAll();
        $folderTitles = [];
        foreach ($folders as $folder) {
            if (isset($folder['title'])) {
                if ($folder['title'] !== '') {
                    $folderTitles[] = $folder['title'];
                } else {
                    $folderTitles[] = 'other';
                }
            }
        }
        return array_unique($folderTitles);
    }

    /**
     * @param array $folders
     * @param $dir
     * @param QueryBuilder $qb
     * @return QueryBuilder
     */
    private function bindWheres(array $folders, $dir, QueryBuilder $qb)
    {
        foreach ($folders as $key => $folder) {
            $qb = $qb
                ->andWhere('i.path NOT LIKE :search' . $key)
                ->setParameter('search' . $key, $dir . $folder . '/%');
        }
        return $qb;
    }

    /**
     * @param $folder
     * @param QueryBuilder $qb
     * @return QueryBuilder
     */
    private function bindWhere($folder, QueryBuilder $qb)
    {
        if ($folder !== null) {
            $qb = $qb
                ->where('i.path LIKE :search')
                ->setParameter('search', $folder . '/%');
            return $qb;
        }
        return $qb;
    }

    public function getRelatedTableRows($shemaName)
    {
        $manager = $this->getEntityManager();
        $connection = $manager->getConnection();
        $query = 'SELECT cols.TABLE_NAME as tab, cols.COLUMN_NAME as col
            FROM `INFORMATION_SCHEMA`.`COLUMNS` as cols
            LEFT JOIN `INFORMATION_SCHEMA`.`KEY_COLUMN_USAGE` AS refs
            ON refs.TABLE_SCHEMA=cols.TABLE_SCHEMA
                    AND refs.REFERENCED_TABLE_SCHEMA=cols.TABLE_SCHEMA
                    AND refs.TABLE_NAME=cols.TABLE_NAME
                    AND refs.COLUMN_NAME=cols.COLUMN_NAME
            WHERE cols.TABLE_SCHEMA="' . $shemaName . '"
                    AND refs.REFERENCED_TABLE_NAME="images"';
        $statement = $connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getUsedCount(array $tables, $id)
    {
        $manager = $this->getEntityManager();
        $connection = $manager->getConnection();
        $usedCount = 0;
        foreach ($tables as $table) {
            $query = 'SELECT COUNT(id) as c FROM ' . $table['tab'] . ' WHERE ' . $table['col'] . ' = ' . $id;
            $statement = $connection->prepare($query);
            $statement->execute();
            $usedCount += (int) $statement->fetchAll()[0]['c'];
        }
        return $usedCount;
    }
}
