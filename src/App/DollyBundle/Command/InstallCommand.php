<?php

namespace App\DollyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Class InstallCommand.
 */
class InstallCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('app:dolly:install')
            ->setDescription('Installs the icon fonts');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $destinationDir = $this->getDestinationDir();
        $srcDir = $this->getSrcDir();

        if (!$this->copyFiles($srcDir, $destinationDir, $output)) {
            return;
        }

        $destinationDir = $this->getDestinationDir().'/bootstrap';
        $srcDir = $this->getBootstrapSrcDir();

        $this->copyFiles($srcDir, $destinationDir, $output);
    }

    /**
     * @return string
     */
    protected function getBootstrapSrcDir()
    {
        return sprintf(
            '%s/%s',
            $this->getContainer()->getParameter('app_dolly.resources.path'),
            'public/fonts/bootstrap'
        );
    }

    /**
     * @return string
     */
    protected function getSrcDir()
    {
        return sprintf(
            '%s/%s',
            $this->getContainer()->getParameter('app_dolly.resources.path'),
            'public/fonts'
        );
    }

    /**
     * @return string
     */
    protected function getDestinationDir()
    {
        return $this->getContainer()->getParameter('app_dolly.fonts_dir');
    }

    /**
     * @param string          $srcDir
     * @param string          $destinationDir
     * @param OutputInterface $output
     *
     * @return bool True on success
     */
    private function copyFiles($srcDir, $destinationDir, OutputInterface $output = null)
    {
        $finder = new Finder();
        $fileSystem = new Filesystem();

        try {
            $fileSystem->mkdir($destinationDir);
        } catch (IOException $e) {
            if (!is_null($output)) {
                $output->writeln(sprintf('<error>Could not create directory %s.</error>', $destinationDir));
            }

            return false;
        }

        if (false === file_exists($srcDir)) {
            if (!is_null($output)) {
                $output->writeln(
                    sprintf(
                        '<error>Fonts directory "%s" does not exist. Did you forget to add fonts into Dolly bundle?</error>',
                        $srcDir
                    )
                );
            }

            return false;
        }
        $finder->files()->in($srcDir);

        foreach ($finder as $file) {
            $destination = sprintf('%s/%s', $destinationDir, $file->getBaseName());
            try {
                $fileSystem->copy($file, $destination);
            } catch (IOException $e) {
                if (!is_null($output)) {
                    $output->writeln(sprintf('<error>Could not copy %s</error>', $file->getBaseName()));
                }

                return false;
            }
        }

        if (!is_null($output)) {
            $output->writeln(
                sprintf(
                    'Dolly: copied %d icon fonts from <comment>%s</comment> to <comment>%s</comment>.',
                    $finder->count(),
                    $srcDir,
                    $destinationDir
                )
            );
        }

        return true;
    }
}
