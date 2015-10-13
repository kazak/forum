<?php
/**
 * @author:     aat <aat@norse.digital>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 11 09 2015
 */

namespace App\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RestaurantTimesDefaultSetter.
 */
class RestaurantTimesDefaultSetterCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('restaurant:times:default:setter')
            ->setDescription('Set default all times and voided delivery guaranty');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $restaurantRepository = $this->getContainer()
            ->get('doctrine')
            ->getRepository('AppCoreBundle:Restaurant');
        $defaultParams = $this->getContainer()->getParameter('app_core.restaurant_default_values');
        $set = $restaurantRepository->setDefaultTimes($defaultParams);
        $output->writeln('Updated '.$set.' rows');
        $output->writeln("OK\n");
    }
    
}
