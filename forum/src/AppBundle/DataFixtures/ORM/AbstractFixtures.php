<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture as Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

abstract class AbstractFixtures extends Fixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var Container
     */
    protected $container;

    /** @var array */
    protected $autoGeneratedFixtures = [];

    /**
     * {@inheritdoc}
     */
    public function setContainer(Container $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param array $data
     * @return mixed
     */
    abstract protected function createEntity($data);

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->processEntities($manager);

        if (!$this->isTestMode()) {
            $manager->flush();
        }
    }

    /**
     * @return array
     */
    protected function getFixturesFromJsonFile()
    {
        $fileName = __DIR__ . '/_data/' . (new \ReflectionClass($this))->getShortName() . '.json';

        if (!file_exists($fileName)) {
            return [];
        }

        return json_decode(
            file_get_contents($fileName),
            true
        );
    }

    /**
     * @param ObjectManager $manager
     */
    protected function processEntities(ObjectManager $manager)
    {
        foreach (array_merge($this->autoGeneratedFixtures, $this->getFixturesFromJsonFile()) as $data) {
            $this->processEntity($manager, $data);
        }
    }

    /**
     * @return bool
     */
    protected function isTestMode()
    {
        return $this->container->getParameter('kernel.environment') == 'test';
    }

    /**
     * @param ObjectManager $manager
     * @param $data
     */
    protected function processEntity(ObjectManager $manager, $data)
    {
        $entity = $this->createEntity($data);
        if (!is_object($entity)) {
            return;
        }
        $manager->persist($entity);
        $this->setReference($data['referenceName'], $entity);
    }
}