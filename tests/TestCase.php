<?php

namespace OctoLab\Cleaner;

use Symfony\Component\Yaml\Yaml;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return string
     */
    protected function getFixturePath()
    {
        return __DIR__ . '/fixtures';
    }

    /**
     * @return string
     */
    protected function getMatcherTestCasePath()
    {
        return $this->getFixturePath() . '/testcases/matcher';
    }

    /**
     * @return string
     */
    protected function getNormalizerTestCasePath()
    {
        return $this->getFixturePath() . '/testcases/normalizer';
    }

    /**
     * @return array
     *
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    protected function getPackages()
    {
        return Yaml::parse(file_get_contents($this->getFixturePath() . '/packages.yml'));
    }
}
