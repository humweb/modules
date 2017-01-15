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
    public function getBasePath()
    {
        if (is_null($this->basePath)) {
            $moduleClass    = new ReflectionClass($this);
            $this->basePath = realpath(dirname($moduleClass->getFilename()).'/../');
        }
        return $this->basePath;
    }


    /**
     * Get default resources path
     *
     * @param string $path
     * @param string $extra
     *
     * @return string
     */
    public function getResourcePath($path = '', $extra = '')
    {
        return $this->getBasePath().'/resources/'.trim($path, '/').'/'.$extra;
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
        return $this->getResourcePath('views', $path);
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
        return $this->getResourcePath('assets', $path);
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
        return $this->getResourcePath('lang', $path);
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
        return $this->getResourcePath('config', $path);
    }


    /**
     * Load language
     */
    public function loadLang()
    {
        $this->loadTranslationsFrom($this->getLangPath(), $this->moduleMeta['slug']);
    }


    /**
     * Register a view file namespace.
     *
     * @param  string $path
     * @param  string $namespace
     * @param bool    $prepend
     *
     * @return void
     */
    protected function loadViewsFrom($path, $namespace, $prepend = true)
    {
        $appPath = $this->app['theme']->activeThemePath('modules/'.$namespace);
        //if (is_dir($appPath = $this->app['theme']->activeThemePath('modules/'.$namespace))) {
        $this->app['view']->addNamespace($namespace, $appPath, $prepend);

        if (is_dir($appPath = $this->app->resourcePath().'/views/vendor/'.$namespace)) {
            $this->app['view']->addNamespace($namespace, $appPath, $prepend);
        }

        $this->app['view']->addNamespace($namespace, $path, $prepend);

    }

    /**
     * Load view
     */
    public function loadViews()
    {
        $this->loadViewsFrom($this->getViewsPath(), $this->moduleMeta['slug']);
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
}
