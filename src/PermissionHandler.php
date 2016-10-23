<?php

namespace Humweb\Modules;

use Illuminate\Support\Collection;

/**
 * MenuHandler
 *
 * @package Humweb\Modules
 */
class PermissionHandler
{

    protected $modules;
    protected $permissions;


    /**
     * MenuHandler constructor.
     *
     * @param array $modules
     */
    public function __construct($modules = [])
    {
        $this->modules     = ($modules instanceof Collection) ? $modules : collect($modules);
        $this->permissions = collect();
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function get()
    {

        if (empty($this->menu)) {

            // Import permission items
            $this->modules->each(function ($module, $name) {
                if (method_exists($module, 'getPermissions')) {
                    $this->permissions[$name] = $module->getPermissions();
                }
            });
        }

        return $this->permissions;
    }

}