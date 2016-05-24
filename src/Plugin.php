<?php

declare(strict_types = 1);

namespace OctoLab\Cleaner;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class Plugin implements Capable, EventSubscriberInterface, PluginInterface
{
    /** @var Composer */
    private $composer;
    /** @var IOInterface */
    private $io;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            PackageEvents::POST_PACKAGE_INSTALL => array('handlePackageEvent', 0),
            PackageEvents::POST_PACKAGE_UPDATE => array('handlePackageEvent', 0),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    /**
     * @return array
     */
    public function getCapabilities()
    {
        return array(
            'Composer\Plugin\Capability\CommandProvider' => 'OctoLab\Cleaner\CommandProvider',
        );
    }

    /**
     * @param PackageEvent $event
     */
    public function handlePackageEvent(PackageEvent $event)
    {
        $event->getOperation()->getReason();
    }
}
