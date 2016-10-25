<?php

namespace OctoLab\Cleaner\Util;

use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class FileCleaner implements CleanerInterface
{
    /** @var Filesystem */
    private $filesystem;
    /** @var FinderInterface */
    private $finder;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
        $this->finder = new GlobFinder();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public function clear($packagePath, array $devFiles)
    {
        assert('is_string($packagePath) && is_readable($packagePath)');
        $result = array();
        $this->finder->setCurrentDir($packagePath);
        foreach ($devFiles as $group => $patterns) {
            $files = $this->finder->find($patterns);
            foreach ($files as $file) {
                $result[] = $file;
                $this->filesystem->remove($file);
            }
        }
        return $result;
    }
}
