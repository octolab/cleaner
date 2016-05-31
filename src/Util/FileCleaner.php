<?php

declare(strict_types = 1);

namespace OctoLab\Cleaner\Util;

use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class FileCleaner implements CleanerInterface
{
    /** @var Filesystem */
    private $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * {@inheritdoc}
     */
    public function clear($packagePath, array $devFiles)
    {
        assert('is_string($packagePath) && is_readable($packagePath)');
    }
}
