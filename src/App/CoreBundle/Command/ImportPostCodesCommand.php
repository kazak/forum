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
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ImportDeliveryRoutesCommand.
 */
class ImportPostCodesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:postcode:import')
            ->setDescription('Imports a CSV file with PostCode data.')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'Path to the CSV file to be imported.'
            )

        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        if (!isset($file) || !file_exists($file)) {
            $output->writeln('You must supply the path to the CSV file to be imported!');

            return;
        }

        $output->writeln("1.\tTrying to import the file: $file");

        $data = file_get_contents($file);
        if ($data === false) {
            $output->writeln("\tNot able to get file contents!");

            return;
        }

        $postCodeHandler = $this->getContainer()->get('app_core.postcode.handler');
        $postCodeHandler->import($data, $output);        
    }
}
