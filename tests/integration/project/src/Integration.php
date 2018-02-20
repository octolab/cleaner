<?php

namespace OctoLab\Test;

use Composer\DependencyResolver\DefaultPolicy;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Pool;
use Composer\DependencyResolver\Request;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\Package\CompletePackage;
use Composer\Repository\CompositeRepository;
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
        $composer = $event->getComposer();
        $io = $event->getIO();
        $plugin = new Plugin();
        $plugin->activate($composer, $io);
        foreach ($composer->getRepositoryManager()->getLocalRepository()->getPackages() as $package) {
            if (!$package instanceof CompletePackage) {
                continue;
            }
            $operation = new InstallOperation($package);
            $packageEvent = new PackageEvent(
                PackageEvents::POST_PACKAGE_INSTALL,
                $composer,
                $io,
                $plugin->isDebug(),
                new DefaultPolicy(true, false),
                new Pool(),
                new CompositeRepository(array()),
                new Request(),
                array(),
                $operation
            );
            $plugin->handlePackageEvent($packageEvent);
        }
    }
}
