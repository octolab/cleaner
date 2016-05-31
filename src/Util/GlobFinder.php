<?php

declare(strict_types = 1);

namespace OctoLab\Cleaner\Util;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class GlobFinder implements FinderInterface
{
    /** @var string */
    private $current;

    /**
     * {@inheritdoc}
     *
     * @quality:method [B]
     */
    public function find(array $patterns)
    {
        $before = getcwd();
        $add = array();
        $sub = array();
        chdir($cwd = $this->current);
        foreach ($patterns as $pattern) {
            assert('is_string($pattern)');
            if ($pattern[0] === '!') {
                $sub[] = glob(substr($pattern, 1));
            } else {
                $add[] = glob(ltrim($pattern, '/'));
            }
        }
        chdir($before);
        return array_map(function ($file) use ($cwd) {
            return $cwd . '/' . $file;
        }, array_unique(
            array_diff(
                $add ? call_user_func_array('array_merge', $add) : $add,
                $sub ? call_user_func_array('array_merge', $sub) : $sub
            )
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrentDir($dir)
    {
        $this->current = $dir;
        return $this;
    }
}
