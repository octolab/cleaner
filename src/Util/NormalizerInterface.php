<?php declare(strict_types = 1);

namespace OctoLab\Cleaner\Util;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
interface NormalizerInterface
{
    /**
     * @param array $devFiles
     *
     * @return array
     *
     * @api
     */
    public function normalize(array $devFiles);
}
