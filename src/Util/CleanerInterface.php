<?php declare(strict_types = 1);

namespace OctoLab\Cleaner\Util;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
interface CleanerInterface
{
    /**
     * @param string $packagePath
     * @param array $devFiles
     *
     * @throws \RuntimeException
     *
     * @api
     */
    public function clear($packagePath, array $devFiles);
}
