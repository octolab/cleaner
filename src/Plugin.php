<?php

namespace OctoLab\Cleaner;

use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
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
    /** @var array */
    private $devFiles;

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

        if (($extra = $this->composer->getPackage()->getExtra()) && isset($extra[self::EXTRA_KEY])) {
            $this->devFiles = $this->normalizer->normalize((array)$extra[self::EXTRA_KEY]);
        }

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

        $this->handlePackage($package);
    }

    /**
     * @param PackageInterface $package
     * @throws \Exception
     */
    public function handlePackage(PackageInterface $package)
    {
        $packageExtra = $package->getExtra();
        $devFiles = $this->devFiles;
        if (isset($packageExtra[self::EXTRA_KEY])) {
            $devFiles = array_merge($devFiles, $this->normalizer->normalize((array)$packageExtra[self::EXTRA_KEY]));
        }

        if ($devFiles) {
            $matched = $this->matcher->match($package->getName(), array_keys($devFiles));
            if(!$matched) {
                return;
            }
            $this->io->write(sprintf('<info>Clearing the package %s...</info>', $package->getName()), true);
            try {
                $files = $this->cleaner->clear(
                    $this->composer->getInstallationManager()->getInstallPath($package),
                    array_filter($devFiles, function ($key) use ($matched) {
                        return in_array($key, $matched, true);
                    }, ARRAY_FILTER_USE_KEY)
                );
                if (!$this->isDebug()) {
                    foreach ($files as $file) {
                        $this->io->write(sprintf('<info>-- removed: %s</info>', $file), true);
                    }
                }
            } catch (\Exception $e) {
                throw $e; // debug breakpoint
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
