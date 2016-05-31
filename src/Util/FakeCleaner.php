<?php

declare(strict_types = 1);

namespace OctoLab\Cleaner\Util;

use Composer\IO\IOInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class FakeCleaner implements CleanerInterface
{
    /** @var FinderInterface */
    private $finder;
    /** @var IOInterface */
    private $io;

    /**
     * @param IOInterface $io
     */
    public function __construct(IOInterface $io)
    {
        $this->io = $io;
        $this->finder = new GlobFinder();
    }

    /**
     * {@inheritdoc}
     *
     * @quality:method [D]
     */
    public function clear($packagePath, array $devFiles)
    {
        assert('is_string($packagePath) && is_readable($packagePath)');
        if ($devFiles !== array()) {
            $this->finder->setCurrentDir($packagePath);
            foreach ($devFiles as $group => $files) {
                $this->io->write(sprintf('<info>- add rules from group "%s"</info>', $group), true);
                $result = $this->finder->find($files);
                if ($result) {
                    $this->io->write('<comment>-- files to delete</comment>');
                    foreach ($result as $file) {
                        $this->io->write(sprintf('<comment>--- %s/%s</comment>', $packagePath, $file));
                    }
                } else {
                    $this->io->write('<comment>--- nothing found</comment>');
                }
            }
        } else {
            $this->io->write('<comment>- there is nothing to clear</comment>', true);
        }
    }
}
