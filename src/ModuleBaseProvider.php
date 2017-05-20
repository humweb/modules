<?php

namespace Humweb\Modules;

use Illuminate\Support\ServiceProvider;
use ReflectionClass;

/**
 * ModuleBaseProvider.
 */
class ModuleBaseProvider extends ServiceProvider
{
    protected $moduleMeta = [
        'name'    => '',
        'slug'    => '',
        'version' => '',
        'author'  => '',
        'email'   => '',
        'website' => '',
    ];

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Base path of module.
     *
     * @var string
     */
    protected $basePath;

    /**
     * Available permissions for module.
     *
     * @var array
     */
    protected $permissions = null;


    /**
     * Register the service provider.
     */
    public function register()
    {
    }


    /**
     * Get base path of module
     *
     * @return string
     */
    public function getBasePath($path = '')
    {
        if (is_null($this->basePath)) {
            $moduleClass    = new ReflectionClass($this);
            $this->basePath = realpath(dirname($moduleClass->getFilename()).'/../');
        }

        return $this->basePath.$this->addSeparatorIfNeeded($path);
    }


    /**
     * Get path to language files
     *
     * @param string $path
     *
     * @return string
     */
    public function getLangPath($path = '')
    {
        return $this->getResourcePath('lang'.$this->addSeparatorIfNeeded($path));
    }


    /**
     * Get default resources path
     *
     * @param string $path
     * @param string $extra
     *
     * @return string
     */
    public function getResourcePath($path = '')
    {
        return $this->getBasePath('resources'.$this->addSeparatorIfNeeded($path));
    }


    /**
     * Get path to views
     *
     * @param string $path
     *
     * @return string
     */
    public function getViewsPath($path = '')
    {
        return $this->getResourcePath('views'.$this->addSeparatorIfNeeded($path));
    }


    /**
     * Get path to config files
     *
     * @param string $path
     *
     * @return string
     */
    public function getConfigPath($path = '')
    {
        return $this->getResourcePath('config'.$this->addSeparatorIfNeeded($path));
    }


    /**
     * Get path to assets
     *
     * @param string $path
     *
     * @return string
     */
    public function getAssetsPath($path = '')
    {
        return $this->getResourcePath('assets'.$this->addSeparatorIfNeeded($path));
    }


    /**
     * Load language
     */
    public function loadLang()
    {
        $this->loadTranslationsFrom($this->getLangPath(), $this->moduleMeta['slug']);
    }


    public function publishConfig($config = null)
    {
        if (is_array($config)) {
            foreach ($config as $input => $output) {
                $this->publishes([
                    $this->getConfigPath($input) => config_path($output),
                ]);
            }
        } elseif (is_string($config)) {
            $this->publishes([
                $this->getConfigPath($config) => config_path($this->moduleMeta['slug'].'.php'),
            ]);
        } else {
            $this->publishes([
                $this->getConfigPath('config.php') => config_path($this->moduleMeta['slug'].'.php'),
            ]);
        }
    }


    /**
     * Load view
     */
    public function loadViews()
    {
        $this->loadViewsFrom($this->getViewsPath(), $this->moduleMeta['slug']);
    }


    /**
     * Register a view file namespace.
     *
     * @param  string $path
     * @param  string $namespace
     *
     * @return void
     */
    protected function loadViewsFrom($path, $namespace)
    {
        // Module theme override path
        if ($this->app->bound('theme')) {
            $themePath = $this->app['theme']->activeThemePath('views/modules/'.$namespace);
            $this->app['view']->addNamespace($namespace, $themePath);
        }

        // Module vendor path
        if (is_dir($vendorPath = $this->app->resourcePath().'/views/vendor/'.$namespace)) {
            $this->app['view']->addNamespace($namespace, $vendorPath);
        }

        // Module resources/view path
        $this->app['view']->addNamespace($namespace, $path);
    }


    /**
     * Publish views
     */
    public function publishViews()
    {
        $this->publishes([
            $this->getViewsPath() => base_path('resources/views/vendor/'.$this->moduleMeta['slug']),
        ]);
    }


    /**
     * Publish assets
     */
    public function publishAssets()
    {
        $this->publishes([
            $this->getAssetsPath() => public_path('vendor/'.$this->moduleMeta['slug']),
        ], 'public');
    }


    public function loadMigrations($dir = null)
    {
        $this->loadMigrationsFrom($this->getResourcePath(is_null($dir) ? 'database/migrations' : $dir));
    }


    /**
     * Get modules metadata
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->moduleMeta;
    }


    /**
     * Get modules permissions
     *
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }


    /**
     * Register Service Provider
     *
     * @param $provider
     */
    public function registerProvider($provider)
    {
        $this->app->register($provider);
    }


    protected function addSeparatorIfNeeded($path = '')
    {
        return ($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}
