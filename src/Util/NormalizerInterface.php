<?php declare(strict_types = 1);

namespace OctoLab\Cleaner\Util;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
interface NormalizerInterface
{
    /**
     * @param array $packageConfig
     *
     * @return array
     */
    public function normalize(array $packageConfig);
}
