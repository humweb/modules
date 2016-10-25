<?php

namespace Humweb\Modules;

use Composer\Installer\PackageEvent;

/**
 * ComposerHooks
 *
 * @package Humweb\Modules
 */
class ComposerHooks
{
    public static function scanPackageForModules(PackageEvent $event)
    {
        // Get all installed packages
        $package = $event->getOperation()->getPackage();
        $path    = $event->getComposer()->getInstallationManager()->getInstallPath($package);
        var_dump($path);
    }
}