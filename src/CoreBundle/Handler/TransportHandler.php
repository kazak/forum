<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 28.03.16
 * Time: 15:28
 */

namespace CoreBundle\Handler;

use CoreBundle\Entity\Transport;
use CoreBundle\Model\Handler\EntityHandler;

/**
 * Class TransportHandler
 * @package CoreBundle\Handler
 */
class TransportHandler extends EntityHandler
{
    /**
     * @param array $filters
     * @param array $sorting
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getEntities(array $filters = [], array $sorting = [], $limit = 100, $offset = 0)
    {
        return parent::getEntities($filters, $sorting, $limit, $offset);
    }
}