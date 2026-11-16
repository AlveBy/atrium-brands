<?php

/*
 * This file is part of Atrium (https://github.com/AlveBy/atrium).
 * Copyright (c) 2026 AlveBy. Licensed under the MIT License, see LICENSE.
 */

declare(strict_types=1);

namespace Atrium\Brands\Tests\Feature;

use Atrium\Brands\Database\BrandsDemoSeeder;
use Atrium\Brands\Models\Brand;
use Atrium\Brands\Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;

final class BrandsTest extends TestCase
{
    public function test_the_brands_open_in_the_admin(): void
    {
        $this->assertTrue((new BrandsDemoSeeder)->run());
        $brand = Brand::query()->where('code', 'lumen')->sole();

        $this->actingAs($this->admin(), 'admin');

        $this->get('/admin/brands')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('title', 'Brands')
                ->where('items.total', 3));
        $this->get('/admin/brands/create')->assertOk();
        $this->get("/admin/brands/{$brand->id}/edit")->assertOk();

        Storage::disk('public')->assertExists('demo/lumen.svg');
    }

    public function test_the_demo_is_filled_once(): void
    {
        (new BrandsDemoSeeder)->run();

        $this->assertFalse((new BrandsDemoSeeder)->run());
        $this->assertSame(3, Brand::query()->count());
    }
}
