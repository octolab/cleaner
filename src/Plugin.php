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
use OctoLab\Cleaner\Util\FakeCleaner;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class Plugin implements Capable, EventSubscriberInterface, PluginInterface
{
    const CONFIG_KEY = 'octolab/cleaner';
    const EXTRA_KEY = 'dev-files';

    /** @var Util\CleanerInterface */
    private $cleaner;
    /** @var Composer */
    private $composer;
    /** @var Config\PluginConfig */
    private $config;
    /** @var IOInterface */
    private $io;
    /** @var Util\MatcherInterface */
    private $matcher;
    /** @var Util\NormalizerInterface */
    private $normalizer;

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
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
        $this->config = $config = new Config\PluginConfig((array)$composer->getConfig()->get(self::CONFIG_KEY));
        $this->cleaner = $config->isDebug() ? new FakeCleaner($io) : $config->getCleaner();
        $this->matcher = $config->getMatcher();
        $this->normalizer = $config->getNormalizer();
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
     *
     * @throws \Exception
     *
     * @quality:method [C]
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
        if (isset($packageExtra[self::EXTRA_KEY])) {
            $normalized = $this->normalizer->normalize((array)$packageExtra[self::EXTRA_KEY]);
            $matched = $this->matcher->match($package->getName(), array_keys($normalized));
            $this->io->write(sprintf('<info>Start clearing the package %s...</info>', $package->getName()), true);
            try {
                $files = $this->cleaner->clear(
                    $this->composer->getInstallationManager()->getInstallPath($package),
                    array_filter($normalized, function ($key) use ($matched) {
                        return in_array($key, $matched, true);
                    }, ARRAY_FILTER_USE_KEY)
                );
                if (!$this->isDebug()) {
                    foreach ($files as $file) {
                        $this->io->write(sprintf('<info>-- file %s was removed</info>', $file), true);
                    }
                }
            } catch (\Exception $e) {
                // debug
                throw $e;
            }
        }
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return $this->config->isDebug();
    }
}
