<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 28.03.16
 * Time: 15:22
 */

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\News;
use CoreBundle\Entity\Region;

/**
 * Class NewsFixtures
 * @package CoreBundle\DataFixtures\ORM
 */
class NewsFixtures extends AbstractForumFixture
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }

    /**
     * @param array $data
     * @return News
     */
    protected function createEntity($data)
    {
        /** @var News $news */
        $news = $this->container->get('news.handler')->createEntity();

        $news->setTitle($data['title'])
            ->setDescription($data['description'])
            ->setStartPage(true)
            ->setRegion($this->getReference($data['region']));

        return $news;
    }
}