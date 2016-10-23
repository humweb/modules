<?php

namespace Humweb\Modules;

use Illuminate\Support\Collection;

/**
 * MenuHandler
 *
 * @package Humweb\Modules
 */
class MenuHandler
{

    protected $menu;
    protected $modules;


    /**
     * MenuHandler constructor.
     *
     * @param array $modules
     * @param array $menu
     */
    public function __construct($modules = [], $menu = [])
    {
        $this->modules = ($modules instanceof Collection) ? $modules : collect($modules);
        $this->menu    = collect(! empty($menu) ? $menu : config('menus.admin.menu'));
    }


    /**
     * @return array|\Illuminate\Support\Collection|mixed
     */
    public function get()
    {

        if (empty($this->menu)) {

            // Import menu items
            $this->modules->each(function ($module) {
                $this->import($module);
            });

            // Remove unused sections
            $this->menu = $this->menu->filter(function ($menu, $key) {
                return ! empty($menu['children']);
            });
        }

        return $this->menu;
    }


    /**
     * @param $module
     */
    protected function import($module)
    {
        if (method_exists($module, 'getAdminMenu')) {
            foreach ($module->getAdminMenu() as $key => $menu) {
                if ( ! isset($this->menu[$key]['children'])) {
                    $this->menu[$key]['children'] = [];
                }
                $this->menu[$key]['children'] = array_merge_recursive($this->menu[$key]['children'], $menu);
            }
        }
    }
}