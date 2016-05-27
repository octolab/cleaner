<?php

declare(strict_types = 1);

namespace OctoLab\Cleaner\Util;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class CategoryNormalizer implements NormalizerInterface
{
    /**
     * @param array $packageConfig
     *
     * @return array
     */
    public function normalize(array $packageConfig)
    {
        $normalizedConfig = array();
        foreach ($packageConfig as $i => $value) {
            if (is_int($i) || $i === 'other') {
                $normalizedConfig['other'] = array_unique(isset($normalizedConfig['other'])
                    ? array_merge($normalizedConfig['other'], (array)$value)
                    : (array)$value);
            } else {
                $normalizedConfig[$i] = (array)$value;
            }
        }
        return $normalizedConfig;
    }
}
