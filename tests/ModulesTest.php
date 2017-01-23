<?php
namespace Humweb\Tests\Modules;

use Humweb\Modules\ModuleBaseProvider;

class ModulesTest extends TestCase
{

    /**
     * @var ExampleServiceProvider
     */
    protected $provider;

    public function setUp()
    {
        parent::setUp();
        $this->provider = new ExampleServiceProvider($this->app);
        $this->provider->boot();
    }

    /**
     * @test
     */
    public function it_builds_admin_menus()
    {
        $this->assertEquals('Example', $this->app['modules']->getAdminMenus()['Settings']['children'][1]['label']);
    }

    /**
     * @test
     */
    public function it_resolves_base_module_path()
    {
        $this->assertEquals(__DIR__, $this->provider->getBasePath());
    }

    /**
     * @test
     */
    public function it_resolves_modules_resource_path()
    {
        $this->assertEquals(__DIR__.'/resources', $this->provider->getResourcePath());
    }

    /**
     * @test
     */
    public function it_resolves_modules_views_path()
    {
        $this->assertEquals(__DIR__.'/resources/views', $this->provider->getViewsPath());
    }

    /**
     * @test
     */
    public function it_resolves_modules_lang_path()
    {
        $this->assertEquals(__DIR__.'/resources/lang', $this->provider->getLangPath());
    }
}


class ExampleServiceProvider extends ModuleBaseProvider
{
    protected $permissions = [

        // Settings
        'example.edit' => [
            'name'        => 'Edit Settings',
            'description' => 'Edit example.',
        ],
    ];

    protected $moduleMeta = [
        'name'    => 'Settings System',
        'slug'    => 'example',
        'version' => '',
        'author'  => '',
        'email'   => '',
        'website' => '',
    ];

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->app['modules']->put('example', $this);
        $this->loadLang();
        $this->loadViews();
        $this->publishViews();
    }

    public function getAdminMenu()
    {
        return [
            'Settings' => [
                [
                    'label' => 'Example',
                    'url'   => '/admin/example',
                    'icon'  => '<i class="fa fa-home" ></i>',
                ],
            ],
        ];
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}
