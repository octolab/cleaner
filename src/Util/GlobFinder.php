<?php

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
        return $this->origin($patterns);
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrentDir($dir)
    {
        $this->current = $dir;
        return $this;
    }

    // @todo rollback logic https://github.com/octolab/Cleaner/issues/33
    private function origin(array $patterns)
    {
        $before = getcwd();
        $add = array();
        $sub = array();
        chdir($cwd = $this->current);
        foreach ($patterns as $pattern) {
            assert(is_string($pattern));
            if (strpos($pattern, '!') === 0) {
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

    // @todo without unit-tests we cannot switch to optimized version
    private function optimized(array $patterns)
    {
        $files = array();
        $before = getcwd();
        chdir($cwd = $this->current);
        foreach ($patterns as $pattern) {
            assert(is_string($pattern));
            if (strpos($pattern, '!') === 0) {
                foreach (glob(substr($pattern, 1)) as $file) {
                    unset($files[$file]);
                }
            } else {
                foreach (glob(ltrim($pattern, '/')) as $file) {
                    $files[$file] = true;
                }
            }
        }
        chdir($before);
        return array_map(function ($file) use ($cwd) {
            return $cwd . '/' . $file;
        }, array_keys($files));
    }
}
