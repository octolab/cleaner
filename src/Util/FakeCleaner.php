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
            chdir($packagePath);
            foreach ($devFiles as $group => $files) {
                $this->io->write(sprintf('<info>- add rules from group "%s"</info>', $group), true);
                $add = array();
                $sub = array();
                foreach ($files as $pattern) {
                    assert('is_string($pattern)');
                    if ($pattern[0] === '!') {
                        $sub[] = glob(substr($pattern, 1));
                    } else {
                        $add[] = glob(ltrim($pattern, '/'));
                    }
                }
                $result = array_unique(
                    array_diff(
                        $add ? call_user_func_array('array_merge', $add) : $add,
                        $sub ? call_user_func_array('array_merge', $sub) : $sub
                    )
                );
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
