<?php

namespace OctoLab\Cleaner\Command;

use OctoLab\Cleaner\TestCase;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class CommandProviderTest extends TestCase
{
    /**
     * @test
     */
    public function getCommands()
    {
        $provider = new CommandProvider();
        foreach ($provider->getCommands() as $command) {
            self::assertInstanceOf('Composer\Command\BaseCommand', $command);
        }
    }
}
