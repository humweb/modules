<?php

namespace Humweb\Modules;

use Composer\Installer\PackageEvent;
use Humweb\Core\Data\JsonFile;

/**
 * ComposerHooks
 *
 * @package Humweb\Modules
 *
 * "post-package-install": [
 * "Humweb\\Modules\\ComposerHooks::scanPackageForModules"
 * ],
 * "post-package-update": [
 * "Humweb\\Modules\\ComposerHooks::scanPackageForModules"
 * ],
 */
class ComposerHooks
{
    public static function addPackage($path)
    {

        $path = rtrim($path, '/').'/module.json';

        if (file_exists($path)) {
            $jsonPackage = new JsonFile($path);
            $jsonCache   = new JsonFile(storage_path('app/modules/cache.json'));

            // Search for existing package and grab index
            $index = $jsonCache->search(function ($item, $key) use ($jsonPackage) {
                return $item['name'] == $jsonPackage->get('name');
            });

            // If we found a index then put the new data to that index
            // If not then just push it to the stack
            if ($index !== false) {
                $jsonCache->put($index, $jsonPackage->all());
            } else {
                $jsonCache->push($jsonPackage->all());
            }

            // Encode the data and write to file
            $jsonCache->write();

            return true;
        }

        return false;
    }


    public static function scanPackageForModules(PackageEvent $event)
    {
        // Get installed package
        $package = $event->getOperation()->getPackage();
        $path    = $event->getComposer()->getInstallationManager()->getInstallPath($package);
        if (static::addPackage($path)) {
            echo "Installed module: ".$package->get."\n";
        }
    }
}