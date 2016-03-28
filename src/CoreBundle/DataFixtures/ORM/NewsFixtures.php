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

class NewsFixtures extends AbstractDollyFixture
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }

    /**
     * @inheritDoc
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