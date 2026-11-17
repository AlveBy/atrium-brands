<?php

/*
 * This file is part of Atrium (https://github.com/AlveBy/atrium).
 * Copyright (c) 2026 AlveBy. Licensed under the MIT License, see LICENSE.
 */

declare(strict_types=1);

namespace Atrium\Brands\Tests\Feature;

use Atrium\Brands\BrandsServiceProvider;
use Atrium\Brands\Database\BrandsDemoSeeder;
use Atrium\Brands\Models\Brand;
use Atrium\Brands\Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

/**
 * What the model asks of the form, what the demo puts into the shop and what
 * an application may publish.
 */
final class BrandsModelTest extends TestCase
{
    public function test_a_brand_is_active_and_sorted_by_default_and_read_back_as_such(): void
    {
        $brand = Brand::query()->create(['name' => 'Lumen', 'code' => 'lumen']);

        $this->assertTrue($brand->fresh()?->active);
        $this->assertSame(500, $brand->fresh()?->sort);
        $this->assertFalse(Brand::query()->create(['name' => 'Off', 'code' => 'off', 'active' => 0])->fresh()?->active);
    }

    public function test_the_form_starts_with_an_active_brand_of_sort_500(): void
    {
        $fields = [];

        foreach (Brand::getAdministrationFields() as $field) {
            $fields[$field->getName()] = $field;
        }

        $this->assertSame(['active', 'name', 'code', 'sort', 'logo', 'color', 'website', 'description'], array_keys($fields));
        $this->assertTrue($fields['active']->getDefault());
        $this->assertSame(500, $fields['sort']->getDefault());
        $this->assertContains('url', $fields['website']->getValidationRules());
        $this->assertContains('nullable', $fields['website']->getValidationRules());
        $this->assertTrue($fields['name']->isRequired());
    }

    public function test_the_list_shows_the_number_the_logo_the_name_and_the_flag(): void
    {
        $this->assertSame(
            ['id', 'logo', 'name', 'active'],
            array_map(fn ($column): string => $column->getName(), Brand::getAdministrationListColumns()),
        );
    }

    public function test_the_demo_brands_have_their_logo_colour_site_and_text(): void
    {
        $this->assertTrue((new BrandsDemoSeeder)->run());

        $brands = Brand::query()->orderBy('id')->get();
        $this->assertSame(['lumen', 'vantor', 'orbita'], $brands->pluck('code')->all());
        $this->assertSame(['Lumen', 'Vantor', 'Orbita'], $brands->pluck('name')->all());
        $this->assertSame(['#1d4ed8', '#b91c1c', '#047857'], $brands->pluck('color')->all());
        $this->assertSame(['https://example.com/lumen', 'https://example.com/vantor', 'https://example.com/orbita'], $brands->pluck('website')->all());
        $this->assertSame("Sound for home and the road.\nHeadphones and speakers tuned by ear.", $brands[1]->description);
        $this->assertStringStartsWith('Laptops and tablets', (string) $brands[0]->description);
        $this->assertStringStartsWith('Watches that count', (string) $brands[2]->description);

        foreach ($brands as $brand) {
            $this->assertSame("demo/{$brand->code}.svg", $brand->logo);
            Storage::disk('public')->assertExists("demo/{$brand->code}.svg");
        }
    }

    public function test_the_texts_can_be_published(): void
    {
        $paths = ServiceProvider::pathsToPublish(BrandsServiceProvider::class, 'atrium-brands-lang');

        $this->assertSame([realpath(dirname(__DIR__, 2).'/src/../lang') => app()->langPath('vendor/atrium-brands')], array_combine(array_map('realpath', array_keys($paths)), $paths));
    }
}
