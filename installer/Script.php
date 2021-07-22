<?php
declare(strict_types=1);

namespace Installer;

use Composer\{Composer,
    IO\IOInterface,
    Plugin\PluginInterface,
    Plugin\PluginEvents,
    Plugin\PreFileDownloadEvent,
    EventDispatcher\EventSubscriberInterface,
    Script\Event,
    Util\Filesystem
};

class Script implements PluginInterface, EventSubscriberInterface
{
    /**
     * @var IOInterface
     */
    protected $io;

    /**
     * @var Composer
     */
    protected $composer;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->io = $io;
        $this->composer = $composer;
    }

    public static function getSubscribedEvents()
    {
        return [
            'post-package-install' => ["onPostPackageInstall", 0]
        ];
    }

    public function onPostPackageInstall($event)
    {
        $filesystem = new Filesystem();
        $testDir = dirname(__DIR__) . '/test';
        $filesystem->remove($testDir);
    }


}
