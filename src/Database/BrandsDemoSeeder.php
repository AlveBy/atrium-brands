<?php

declare(strict_types=1);

namespace Atrium\Brands\Database;

use Atrium\Admin\Modules\DemoSeeder;
use Atrium\Brands\Models\Brand;

/**
 * Three makers of the demo shop's electronics.
 */
final class BrandsDemoSeeder extends DemoSeeder
{
    public function run(): bool
    {
        if (Brand::query()->exists()) {
            return false;
        }

        $this->copyFiles(dirname(__DIR__, 2).'/resources/demo');

        foreach ([
            'lumen' => ['Lumen', '#1d4ed8', 'https://example.com/lumen', 'Laptops and tablets designed for long days away from a socket.'],
            'vantor' => ['Vantor', '#b91c1c', 'https://example.com/vantor', "Sound for home and the road.\nHeadphones and speakers tuned by ear."],
            'orbita' => ['Orbita', '#047857', 'https://example.com/orbita', 'Watches that count steps, sleep and the minutes to the next meeting.'],
        ] as $code => [$name, $color, $website, $description]) {
            Brand::query()->create([
                'name' => $name,
                'code' => $code,
                'logo' => $this->path($code.'.svg'),
                'color' => $color,
                'website' => $website,
                'description' => $description,
            ]);
        }

        return true;
    }
}
