<?php

declare(strict_types = 1);

namespace OctoLab\Cleaner;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class Plugin implements PluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function activate(Composer $composer, IOInterface $io)
    {
    }
}
