<?php

namespace OctoLab\Cleaner;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class PluginTest extends TestCase
{
    /**
     * @test
     */
    public function activate()
    {
        self::assertTrue(true, 'Not implemented yet.');
    }

    /**
     * @test
     */
    public function getCapabilities()
    {
        foreach ($this->getPlugin()->getCapabilities() as $interface => $implementation) {
            self::assertTrue(interface_exists($interface) && class_exists($implementation));
        }
    }

    /**
     * @test
     *
     * @throws \ReflectionException
     */
    public function getSubscribedEvents()
    {
        $plugin = $this->getPlugin();
        $reflection = new \ReflectionClass('Composer\Installer\PackageEvents');
        $constants = $reflection->getConstants();
        foreach ($plugin::getSubscribedEvents() as $event => $method) {
            self::assertContains($event, $constants);
            self::assertInternalType('callable', array($plugin, is_array($method) ? $method[0] : $method));
        }
    }

    /**
     * @test
     */
    public function handlePackageEvent()
    {
        self::assertTrue(true, 'Not implemented yet.');
    }

    /**
     * @return Plugin
     */
    private function getPlugin()
    {
        return new Plugin();
    }
}
