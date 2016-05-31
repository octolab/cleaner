<?php

declare(strict_types = 1);

namespace OctoLab\Cleaner\Util;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class FileCleaner implements CleanerInterface
{
    /** @var Filesystem */
    private $filesystem;
    /** @var Finder */
    private $finder;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
        $this->finder = new Finder();
    }

    /**
     * {@inheritdoc}
     */
    public function clear($packagePath, array $devFiles)
    {
        assert('is_string($packagePath) && is_readable($packagePath)');
        return true;
    }
}
