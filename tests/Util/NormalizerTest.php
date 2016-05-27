<?php

declare(strict_types = 1);

namespace OctoLab\Cleaner\Util;

use OctoLab\Cleaner\TestCase;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class NormalizerTest extends TestCase
{
    /**
     * @test
     * @dataProvider packageConfigProvider
     *
     * @param $normalizer
     * @param array $packageConfig
     * @param array $expectedConfig
     */
    public function normalize(NormalizerInterface $normalizer, array $packageConfig, array $expectedConfig)
    {
        self::assertEquals($expectedConfig, $normalizer->normalize($packageConfig));
    }

    /**
     * @return array
     */
    public function packageConfigProvider()
    {
        $categoryNormalizer = new CategoryNormalizer();
        return array(
            'category:group numeric to "other"' => array(
                $categoryNormalizer,
                array(
                    '/*.md',
                    array('!LICENSE.md'),
                ),
                array(
                    'other' => array('/*.md', '!LICENSE.md'),
                ),
            ),
            'category:convert "other" to array and merge with numeric' => array(
                $categoryNormalizer,
                array(
                    '/*.md',
                    'other' => '!README.md',
                    array('!LICENSE.md'),
                ),
                array(
                    'other' => array('/*.md', '!README.md', '!LICENSE.md'),
                ),
            ),
            'category:merge "other" with numeric and filter not-unique' => array(
                $categoryNormalizer,
                array(
                    '/*.md',
                    'other' => array('!README.md', '!LICENSE.md'),
                    array('!LICENSE.md'),
                ),
                array(
                    'other' => array('/*.md', '!README.md', '!LICENSE.md'),
                ),
            ),
            'category:only "other" presented' => array(
                $categoryNormalizer,
                array(
                    'other' => array('/*.md', '!LICENSE.md'),
                ),
                array(
                    'other' => array('/*.md', '!LICENSE.md'),
                ),
            ),
            'category:normal configuration' => array(
                $categoryNormalizer,
                array(
                    'bin' => array('/bin', '/action/demo.sh'),
                    'docs' => array('/docs'),
                    'tests' => array('/tests'),
                    'other' => array('/examples/*'),
                ),
                array(
                    'bin' => array('/bin', '/action/demo.sh'),
                    'docs' => array('/docs'),
                    'tests' => array('/tests'),
                    'other' => array('/examples/*'),
                ),
            ),
            'category:mixed configuration' => array(
                $categoryNormalizer,
                array(
                    '/*.md',
                    'bin' => array('/bin', '/action/demo.sh'),
                    array('!LICENSE.md'),
                    'docs' => array('/docs'),
                    'tests' => array('/tests'),
                    'other' => array('/examples/*'),
                ),
                array(
                    'bin' => array('/bin', '/action/demo.sh'),
                    'docs' => array('/docs'),
                    'tests' => array('/tests'),
                    'other' => array('/*.md', '!LICENSE.md', '/examples/*'),
                ),
            ),
        );
    }
}
