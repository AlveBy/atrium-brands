<?php

declare(strict_types=1);

namespace Atrium\Brands\Tests\Feature;

use Atrium\Admin\Resources\ResourceRegistry;
use Atrium\Brands\Tests\TestCase;
use Illuminate\Support\Facades\Schema;

/**
 * Required with Composer is not installed: until `atrium:module:install`
 * the module is not in the panel, the site or the database.
 */
final class NotInstalledTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('atrium.modules', []);
    }

    public function test_a_module_required_but_not_installed_is_nowhere(): void
    {
        $this->assertArrayNotHasKey('brands', $this->app->make(ResourceRegistry::class)->all());
        $this->assertFalse(Schema::hasTable('brands'));

        $this->actingAs($this->admin(), 'admin')->get('/admin/brands')->assertNotFound();
    }
}
