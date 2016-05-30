<?php

declare(strict_types = 1);

namespace OctoLab\Cleaner\Command;

use OctoLab\Cleaner\TestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class CleanCommandTest extends TestCase
{
    /**
     * @test
     */
    public function execute()
    {
        $command = new CleanCommand();
        $reflection = new \ReflectionObject($command);
        $method = $reflection->getMethod('execute');
        $method->setAccessible(true);

        $input = new ArgvInput();
        $output = new BufferedOutput();
        self::assertEquals(0, $method->invoke($command, $input, $output));
    }
}
