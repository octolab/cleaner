<?php

namespace OctoLab\Cleaner\Config;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class PluginConfig extends \ArrayObject
{
    /**
     * @param array $config
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $config)
    {
        $default = array(
            'clear' => null,
            'debug' => false,
            'cleaner' => '\OctoLab\Cleaner\Util\FileCleaner',
            'matcher' => '\OctoLab\Cleaner\Util\WeightMatcher',
            'normalizer' => '\OctoLab\Cleaner\Util\CategoryNormalizer',
        );
        parent::__construct(
            $this->validate(array_merge(
                $default,
                array_intersect_key($config, $default)
            ))
        );
    }

    /**
     * @return \OctoLab\Cleaner\Util\CleanerInterface
     */
    public function getCleaner()
    {
        return new $this['cleaner']();
    }

    /**
     * @return \OctoLab\Cleaner\Util\MatcherInterface
     */
    public function getMatcher()
    {
        /** @var \OctoLab\Cleaner\Util\MatcherInterface $matcher */
        $matcher = new $this['matcher']();
        return $matcher->setRules((array)$this['clear']);
    }

    /**
     * @return \OctoLab\Cleaner\Util\NormalizerInterface
     */
    public function getNormalizer()
    {
        return new $this['normalizer']();
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return (bool)$this['debug'];
    }

    /**
     * @param array $config
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    private function validate(array $config)
    {
        $isValid = (int)!is_array($config['clear']);
        $interfaces = array(
            'cleaner' => 'OctoLab\Cleaner\Util\CleanerInterface',
            'matcher' => 'OctoLab\Cleaner\Util\MatcherInterface',
            'normalizer' => 'OctoLab\Cleaner\Util\NormalizerInterface',
        );
        foreach ($interfaces as $key => $interface) {
            $isValid |= (int)!is_subclass_of($config[$key], $interface, true);
        }
        if ($isValid === 1) {
            throw new \InvalidArgumentException('The plugin configuration is invalid.');
        }
        return $config;
    }
}
