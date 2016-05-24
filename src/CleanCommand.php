<?php

declare(strict_types = 1);

namespace OctoLab\Cleaner;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class CleanCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('octolab:clean')
            ->addOption('package', 'p', InputOption::VALUE_OPTIONAL, 'Package for cleaning.')
            ->addOption('categories', 'c', InputOption::VALUE_OPTIONAL, 'Categories of dev files separated by commas.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $package = $input->getOption('package');
        if ($package !== null) {
            // clean provided package
        } else {
            // clean all installed packages
        }
        return 0;
    }
}
