<?php

declare(strict_types = 1);

namespace OctoLab\Test;

use Composer\Script\Event;
use OctoLab\Cleaner\Plugin;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class Integration
{
    /**
     * @param Event $event
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function run(Event $event)
    {
        $plugin = new Plugin();
        $plugin->activate($event->getComposer(), $event->getIO());
    }
}
