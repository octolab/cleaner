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
    /** @var array */
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
        $default = array(
            'clear' => array(),
            'debug' => false,
            'cleaner' => 'OctoLab\Cleaner\Util\FileCleaner',
            'matcher' => 'OctoLab\Cleaner\Util\WeightMatcher',
            'normalizer' => 'OctoLab\Cleaner\Util\CategoryNormalizer',
        );
        $this->config = $this->validate(array_merge(
            $default,
            array_intersect_key((array)$composer->getConfig()->get(self::CONFIG_KEY), $default)
        ));
        $this->cleaner = $this->isDebug() ? new FakeCleaner($io) : new $this->config['cleaner']();
        $this->composer = $composer;
        $this->io = $io;
        $this->matcher = new $this->config['matcher']();
        $this->normalizer = new $this->config['normalizer']();
        $this->matcher->setRules($this->config['clear']);
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
     * @quality:method [B]
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
        return (bool)$this->config['debug'];
    }

    /**
     * @param array $config
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     *
     * @quality:method [B]
     */
    private function validate(array $config)
    {
        if (!is_array($config['clear'])
            || !is_subclass_of($config['cleaner'], 'OctoLab\Cleaner\Util\CleanerInterface', true)
            || !is_subclass_of($config['matcher'], 'OctoLab\Cleaner\Util\MatcherInterface', true)
            || !is_subclass_of($config['normalizer'], 'OctoLab\Cleaner\Util\NormalizerInterface', true)
        ) {
            throw new \InvalidArgumentException(sprintf('The %s configuration is invalid.', self::CONFIG_KEY));
        }
        return $config;
    }
}
