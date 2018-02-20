<?php

namespace OctoLab\Cleaner\Util;

use OctoLab\Cleaner\TestCase;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class NormalizerTest extends TestCase
{
    /**
     * @return array
     */
    public function cases()
    {
        $cases = array();
        $normalizer = new CategoryNormalizer();
        foreach (glob($this->getNormalizerTestCasePath() . '/category/*/') as $folder) {
            $cases[] = array($normalizer, $folder);
        }
        return $cases;
    }

    /**
     * @test
     * @dataProvider cases
     *
     * @param NormalizerInterface $normalizer
     * @param string $folder
     */
    public function normalize(NormalizerInterface $normalizer, $folder)
    {
        $testCase = array_replace_recursive(
            array(
                'title' => 'unknown test',
                'message' => 'unknown test message',
                'description' => 'unknown test description',
                'extra' => array(
                    'dev-files' => array(),
                ),
            ),
            Yaml::parse(file_get_contents($folder . '/setup.yml'))
        );
        self::assertEquals(
            Yaml::parse(file_get_contents($folder . '/expected.yml')),
            $normalizer->normalize($testCase['extra']['dev-files']),
            sprintf('%s (%s: %s)', $testCase['message'], $testCase['title'], $testCase['description'])
        );
    }
}
