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
     */
    public function clean($packagePath, array $devFiles)
    {
        assert('is_string($packagePath) && is_readable($packagePath)');
        if ($devFiles !== array()) {
            $finder = new Finder();
            $finder->followLinks()->in($packagePath);
            foreach ($devFiles as $group => $files) {
                $this->io->write(sprintf('<info>- add rules from %s group</info>', $group), true);
                foreach ($files as $file) {
                    assert('is_string($file)');
                    if ($file[0] === '!') {
                        $file = substr($file, 1);
                        $finder->notName($file);
                        $finder->notPath($file);
                    } else {
                        $file = ltrim($file, '/');
                        $finder->name($file);
                        $finder->name($file);
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
            $this->io->write('<comment>- there is nothing to clean</comment>', true);
        }
        return true;
    }
}
