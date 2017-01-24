<?php

namespace Humweb\Tests\Modules;

use Humweb\Modules\ModuleServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{

    protected function getPackageProviders($app)
    {
        return [
            ModuleServiceProvider::class
        ];
    }

}