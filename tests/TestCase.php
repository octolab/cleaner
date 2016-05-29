<?php

declare(strict_types = 1);

namespace OctoLab\Cleaner;

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
}
