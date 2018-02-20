<?php

namespace OctoLab\Cleaner\Util;

use OctoLab\Cleaner\TestCase;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class MatcherTest extends TestCase
{
    /**
     * @return array
     */
    public function cases()
    {
        $cases = array();
        $matcher = new WeightMatcher();
        $normalizer = new CategoryNormalizer();
        foreach (glob($this->getMatcherTestCasePath() . '/weight/*/') as $folder) {
            $cases[] = array($matcher, $normalizer, $folder);
        }
        return $cases;
    }

    /**
     * @test
     * @dataProvider cases
     *
     * @param MatcherInterface $matcher
     * @param NormalizerInterface $normalizer
     * @param string $folder
     */
    public function match(MatcherInterface $matcher, NormalizerInterface $normalizer, $folder)
    {
        $testCase = array_replace_recursive(
            array(
                'title' => 'unknown test',
                'message' => 'unknown test message',
                'description' => 'unknown test description',
                'config' => array(
                    'octolab/cleaner' => array(
                        'clean' => array(),
                    ),
                ),
            ),
            Yaml::parse(file_get_contents($folder . '/setup.yml'))
        );
        $matcher->setRules($testCase['config']['octolab/cleaner']['clean']);
        $expected = Yaml::parse(file_get_contents($folder . '/expected.yml'));
        foreach ($this->getPackages() as $package => $devFiles) {
            self::assertEquals(
                $expected[$package],
                $matcher->match($package, array_keys($normalizer->normalize($devFiles))),
                sprintf('%s: %s (%s: %s)', $package, $testCase['message'], $testCase['title'], $testCase['description'])
            );
        }
    }
}
