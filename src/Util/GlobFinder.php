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
        $files = array();
        $before = getcwd();
        chdir($cwd = $this->current);
        foreach ($patterns as $pattern) {
            assert('is_string($pattern)');
            if ($pattern[0] === '!') {
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

    /**
     * {@inheritdoc}
     */
    public function setCurrentDir($dir)
    {
        $this->current = $dir;
        return $this;
    }
}
