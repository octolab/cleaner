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
    /** @var IOInterface */
    private $io;

    /**
     * @param IOInterface $io
     */
    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    /**
     * {@inheritdoc}
     *
     * @quality:method [C]
     */
    public function clear($packagePath, array $devFiles)
    {
        assert('is_string($packagePath) && is_readable($packagePath)');
        if ($devFiles !== array()) {
            $finder = new Finder();
            $finder->in($packagePath);
            foreach ($devFiles as $group => $files) {
                $this->io->write(sprintf('<info>- add rules from group "%s"</info>', $group), true);
                foreach ($files as $file) {
                    assert('is_string($file)');
                    if ($file[0] === '!') {
                        $finder->notName(substr($file, 1));
                    } else {
                        $finder->name(ltrim($file, '/'));
                    }
                }
            }
            $this->io->write('<comment>-- files to delete</comment>');
            if (count($finder) > 0) {
                /** @var \Symfony\Component\Finder\SplFileInfo $file */
                foreach ($finder as $file) {
                    $this->io->write(sprintf('<comment>--- %s</comment>', $file->getRealPath()));
                }
            } else {
                $this->io->write('<comment>--- nothing found</comment>');
            }
        } else {
            $this->io->write('<comment>- there is nothing to clear</comment>', true);
        }
        return true;
    }
}
