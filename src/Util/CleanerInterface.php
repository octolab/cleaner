<?php

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
     * @return string[]
     *
     * @throws \RuntimeException
     *
     * @api
     */
    public function clear($packagePath, array $devFiles);
}
