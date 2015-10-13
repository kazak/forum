<?php

/**
 * @author:     me <marius@norse.digital>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 22 05 2015
 */
namespace App\BackOfficeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class OSImportAllCommand
 * @package App\OpenSolutionBundle\Command
 */
class SiteMapCommand extends ContainerAwareCommand
{
    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('app:generate:site_map')
            ->setDescription('generate site map');
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

        $host = $this->getContainer()->getParameter('sitemap.url.host');
        $this->getContainer()->get('app_core.sitemap.handler')->addListeners();
        $this->getContainer()
            ->get('presta_sitemap.dumper')
            ->dump('sitemap/',
                $host,
                'default',
                ['target' => 'sitemap/']
            );

        $output->writeln('Finished.');
    }
}
