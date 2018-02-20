<?php

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
     * @quality:method [B]
     */
    public function clear($packagePath, array $devFiles)
    {
        assert(is_string($packagePath));
        assert(is_readable($packagePath));
        $result = array();
        if ($devFiles !== array()) {
            $this->finder->setCurrentDir($packagePath);
            foreach ($devFiles as $group => $patterns) {
                $this->io->write(sprintf('<info>- add rules from group "%s"</info>', $group), true);
                $files = $this->finder->find($patterns);
                if ($files) {
                    $this->io->write('<comment>-- files to delete</comment>');
                    foreach ($files as $file) {
                        $result[] = $file;
                        $this->io->write(sprintf('<comment>--- %s</comment>', $file));
                    }
                } else {
                    $this->io->write('<comment>--- nothing found</comment>');
                }
            }
        } else {
            $this->io->write('<comment>- there is nothing to clear</comment>', true);
        }
        return $result;
    }
}
