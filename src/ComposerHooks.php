<?php

namespace Humweb\Modules;

use Composer\Script\Event;

/**
 * ComposerHooks
 *
 * @package Humweb\Modules
 */
class ComposerHooks
{
    public static function scanModules(Event $event)
    {
        // Get all installed packages
        $packages            = $event->getComposer()->getRepositoryManager()->getLocalRepository()->getPackages();
        $installationManager = $event->getComposer()->getInstallationManager();
        $installPath         = [];

        foreach ($packages as $package) {
            $installPath[] = $installationManager->getInstallPath($package);
            //do my process here
        }
        var_dump($installPath);
    }
}