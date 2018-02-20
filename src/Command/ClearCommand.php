<?php

namespace OctoLab\Cleaner\Command;

use Composer\Command\BaseCommand;
use OctoLab\Cleaner\Plugin;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
// @todo support debug option
//use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class ClearCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('octolab:clear')
            ->setDescription('Remove development files from installed packages')
            ->addArgument('package', InputArgument::OPTIONAL, 'Package for cleaning.')
            // @todo
            //->addArgument('categories', InputArgument::OPTIONAL, 'Categories of dev files separated by commas.')
            // @todo add possibility to change debug mode in the plugin (and hence its cleaner)
            //->addOption('debug', null, InputOption::VALUE_OPTIONAL, 'Debug mode - only display files which would be deleted, but do not delete them')
        ;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $composer = $this->getComposer();

        // @todo is this a proper way to get current plugin instance?
        $plugin = null;
        foreach ($composer->getPluginManager()->getPlugins() as $p) {
            if ($p instanceof Plugin) {
                $plugin = $p;
                break;
            }
        }

        $repo = $composer->getRepositoryManager()->getLocalRepository();

        $packageFilter = $input->getArgument('package');
        if ($packageFilter && false === strpos($packageFilter, '*')) {
            $packages = $repo->findPackages($packageFilter, '*');

            if (!$packages) {
                throw new \InvalidArgumentException('No packages found for "'.$packageFilter.'"');
            }
        } else {
            $packages = $repo->getPackages();
        }

        foreach ($packages as $package) {
            $plugin->handlePackage($package);
        }

        return 0;
    }
}
