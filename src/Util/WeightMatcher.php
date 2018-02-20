<?php

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

    /** @var array */
    private $rules;

    /**
     * {@inheritdoc}
     */
    public function match($package, array $devFileGroups)
    {
        assert(is_string($package));
        $sortedRules = array();
        $packageNamespace = false !== ($pos = strpos($package, '/')) ? substr($package, 0, $pos) : '';
        if (isset($this->rules[$package])) {
            $sortedRules = $this->sort((array)$this->rules[$package]);
        } elseif ($packageNamespace && isset($this->rules[$packageNamespace.'/*'])) {
            $sortedRules = $this->sort((array)$this->rules[$packageNamespace.'/*']);
        } elseif (isset($this->rules['*'])) {
            $sortedRules = $this->sort((array)$this->rules['*']);
        }
        return array_values($this->apply($devFileGroups, $sortedRules));
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
     *
     * @quality:method [B]
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
            assert(is_string($rule));
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
