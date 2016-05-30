<?php

declare(strict_types = 1);

namespace OctoLab\Cleaner;

use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
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
    const EXTRA_KEY = 'dev-files';

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
            'Composer\Plugin\Capability\CommandProvider' => 'OctoLab\Cleaner\Command\CommandProvider',
        );
    }

    /**
     * @param PackageEvent $event
     */
    public function handlePackageEvent(PackageEvent $event)
    {
        $operation = $event->getOperation();
        if ($operation instanceof InstallOperation) {
            $package = $operation->getPackage();
        } elseif ($operation instanceof UpdateOperation) {
            $package = $operation->getTargetPackage();
        } else {
            return;
        }
        $packageExtra = $package->getExtra();
        if (isset($packageExtra[self::EXTRA_KEY]) && is_array($packageExtra[self::EXTRA_KEY])) {
            //
        } else {
            return;
        }
    }
}
