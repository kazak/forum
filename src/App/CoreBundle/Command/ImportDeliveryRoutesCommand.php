<?php

/**
 * @author:     Lars Fugelseth <lars@norse.digital>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 15 07 2015
 */
namespace App\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportDeliveryRoutesCommand.
 */
class ImportDeliveryRoutesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:core:import:deliveryroutes')
            ->setDescription('Import/Update delivery routes from Arc GIS online.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting...');

        $this->getContainer()->get('app_core.deliveryroute.import')->run($output);

        $output->writeln('Finished.');
    }
}
