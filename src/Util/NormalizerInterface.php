<?php

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
