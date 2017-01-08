<?php
namespace Humweb\Tests\Modules;

use Humweb\Modules\ComposerHooks;

/**
 * ComposerHooks.php
 *
 * Author: ryan
 * Date:   10/25/16
 * Time:   2:07 AM
 */
class ComposerHooksTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        app('path')->set('storage', __DIR__.'/tmp');
    }

    /**
     * @test
     */
    function it_finds_module_file() {
        ComposerHooks::scanPackageForModules(__DIR__.'/stub');
    }

}
