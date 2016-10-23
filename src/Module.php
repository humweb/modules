<?php

namespace Humweb\Modules;

use Illuminate\Support\Collection;

/**
 * Module.
 */
class Module extends Collection
{
    protected $adminMenus = [];
    protected $availablePermissions = [];

    public function getAdminMenus()
    {

        if (empty($this->adminMenus)) {
            $this->adminMenus = config('menus.admin.sections');

            //Create menu array for admin panel
            foreach ($this->items as $name => $module) {
                $this->importAdminMenus($module);
            }

            // Remove unused
            foreach ($this->adminMenus as $k => $v) {
                if (empty($v['children'])) unset($this->adminMenus[$k]);
            }
        }

        return $this->adminMenus;
    }

    public function getAvailablePermissions()
    {
        if (empty($this->availablePermissions)) {

            //Create menu array for admin panel
            foreach ($this->items as $name => $module) {
                if ($perms = $module->getPermissions()) {
                    $this->availablePermissions[$name] = $perms;
                }
            }
        }

        return $this->availablePermissions;
    }


    /**
     * @param $module
     */
    protected function importAdminMenus($module)
    {
        if (method_exists($module, 'getAdminMenu')) {
            foreach ($module->getAdminMenu() as $key => $menu) {
                if (!isset($this->adminMenus[$key]['children'])) {
                    $this->adminMenus[$key]['children'] = [];
                }
                $this->adminMenus[$key]['children'] = array_merge_recursive($this->adminMenus[$key]['children'], $menu);
            }
        }
    }
}
