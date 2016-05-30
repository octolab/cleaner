<?php

declare(strict_types = 1);

namespace OctoLab\Cleaner\Util;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class WeightMatcher implements MatcherInterface
{
    const BLANK = 0;
    const DENIAL = 1;
    const ENUM = 2;
    const ALL = 3;

    /** @var NormalizerInterface */
    private $normalizer;
    /** @var array */
    private $rules;

    /**
     * @param NormalizerInterface $normalizer
     */
    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function match($package, array $devFiles)
    {
        assert('is_string($package)');
        $keys = array_keys($this->normalizer->normalize($devFiles));
        $sorted = array();
        if (isset($this->rules[$package])) {
            $sorted = $this->sort((array)$this->rules[$package]);
        } elseif (isset($this->rules['*'])) {
            $sorted = $this->sort((array)$this->rules['*']);
        }
        return array_values($this->apply($keys, $sorted));
    }

    /**
     * {@inheritdoc}
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * @param array $keys
     * @param array $rules
     *
     * @return array
     */
    private function apply(array $keys, array $rules)
    {
        $winner = array_filter($rules, function ($value) {
            return !empty($value);
        });
        reset($winner);
        $type = key($winner);
        $rule = current($winner);
        switch ($type) {
            case self::DENIAL:
                return array_filter($keys, function ($value) use ($rule) {
                    return $value !== $rule;
                });
            case self::ENUM:
                return array_intersect($keys, $rule);
            case self::ALL:
                return $keys;
            default:
                return array();
        }
    }

    /**
     * @param array $rules
     *
     * @return array
     */
    private function sort(array $rules)
    {
        $sorted = array(
            self::BLANK => $rules === array(),
            self::DENIAL => null,
            self::ENUM => array(),
            self::ALL => false,
        );
        foreach ($rules as $rule) {
            assert('is_string($rule)');
            switch (true) {
                case $rule === '*':
                    $sorted[self::ALL] = true;
                    break 1;
                case $rule[0] === '!':
                    $sorted[self::DENIAL] = substr($rule, 1);
                    break 2;
                default:
                    $sorted[self::ENUM][] = $rule;
            }
        }
        return $sorted;
    }
}
