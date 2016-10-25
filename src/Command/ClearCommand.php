<?php

namespace OctoLab\Cleaner\Command;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
            ->addOption('package', 'p', InputOption::VALUE_OPTIONAL, 'Package for cleaning.')
            ->addOption('categories', 'c', InputOption::VALUE_OPTIONAL, 'Categories of dev files separated by commas.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return 0;
    }
}
