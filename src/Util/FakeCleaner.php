<?php

declare(strict_types = 1);

namespace OctoLab\Cleaner\Util;

use Composer\IO\IOInterface;
use Symfony\Component\Finder\Finder;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class FakeCleaner implements CleanerInterface
{
    /** @var Finder */
    private $finder;
    /** @var IOInterface */
    private $io;

    /**
     * @param IOInterface $io
     */
    public function __construct(IOInterface $io)
    {
        $this->io = $io;
        $this->finder = new Finder();
    }

    /**
     * {@inheritdoc}
     */
    public function clean($packagePath, array $devFiles)
    {
        assert('is_string($packagePath) && is_readable($packagePath)');
        return true;
    }
}
