<?php

declare(strict_types = 1);

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
        $matcher = new WeightMatcher(new CategoryNormalizer());
        foreach (glob($this->getMatcherTestCasePath() . '/weight/*/') as $folder) {
            $cases[] = array($matcher, $folder);
        }
        return $cases;
    }

    /**
     * @test
     */
    public function construct()
    {
        new WeightMatcher(new CategoryNormalizer());
    }

    /**
     * @test
     * @dataProvider cases
     *
     * @param MatcherInterface $matcher
     * @param string $folder
     */
    public function match(MatcherInterface $matcher, $folder)
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
                $matcher->match($package, $devFiles),
                sprintf('%s: %s (%s: %s)', $package, $testCase['message'], $testCase['title'], $testCase['description'])
            );
        }
    }
}
