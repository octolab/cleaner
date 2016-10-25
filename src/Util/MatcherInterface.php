<?php

namespace OctoLab\Cleaner\Util;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
interface MatcherInterface
{
    /**
     * @param string $package
     * @param array $devFileGroups
     *
     * @return array
     *
     * @api
     */
    public function match($package, array $devFileGroups);

    /**
     * @param array $rules
     *
     * @return MatcherInterface
     *
     * @api
     */
    public function setRules(array $rules);
}
