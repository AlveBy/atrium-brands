<?php

/*
 * This file is part of Atrium (https://github.com/AlveBy/atrium).
 * Copyright (c) 2026 AlveBy. Licensed under the MIT License, see LICENSE.
 */

declare(strict_types=1);

namespace Atrium\Brands\Models;

use Atrium\Admin\Columns\BooleanColumn;
use Atrium\Admin\Columns\ImageColumn;
use Atrium\Admin\Columns\TextColumn;
use Atrium\Admin\Contracts\Administrable;
use Atrium\Admin\Fields\CheckboxField;
use Atrium\Admin\Fields\ColorField;
use Atrium\Admin\Fields\ImageField;
use Atrium\Admin\Fields\NumberField;
use Atrium\Admin\Fields\SlugField;
use Atrium\Admin\Fields\TextareaField;
use Atrium\Admin\Fields\TextField;
use Atrium\Admin\Traits\InteractsWithAdmin;
use Illuminate\Database\Eloquent\Model;

/**
 * A directory other modules link to. It has no page of its own: a product
 * shows the brand's name.
 *
 * @property int $id
 * @property string $name
 * @property string $code
 */
class Brand extends Model implements Administrable
{
    use InteractsWithAdmin;

    protected $table = 'brands';

    protected $fillable = ['name', 'code', 'active', 'sort', 'logo', 'color', 'website', 'description'];

    protected function casts(): array
    {
        return ['active' => 'boolean', 'sort' => 'integer'];
    }

    public static function getAdminLabel(): string
    {
        return __('atrium-brands::brands.module');
    }

    public static function getAdminRoutePrefix(): string
    {
        return 'brands';
    }

    public static function getAdministrationFields(): array
    {
        return [
            CheckboxField::make('active')->label(__('atrium::fields.standard.active'))->default(true),
            TextField::make('name')->label(__('atrium::fields.standard.name'))->required(),
            SlugField::make('code')->label(__('atrium::fields.standard.code'))->from('name')->unique('brands', 'code'),
            NumberField::make('sort')->label(__('atrium::fields.standard.sort'))->default(500),
            ImageField::make('logo')->label(__('atrium-brands::brands.fields.logo'))->directory('brands'),
            ColorField::make('color')->label(__('atrium-brands::brands.fields.color')),
            TextField::make('website')->label(__('atrium-brands::brands.fields.website'))->rules(['nullable', 'url']),
            TextareaField::make('description')->label(__('atrium::fields.standard.description')),
        ];
    }

    public static function getAdministrationListColumns(): array
    {
        return [
            TextColumn::make('id')->label(__('atrium::fields.standard.id'))->sortable(),
            ImageColumn::make('logo')->label(__('atrium-brands::brands.fields.logo')),
            TextColumn::make('name')->label(__('atrium::fields.standard.name'))->sortable()->searchable(),
            BooleanColumn::make('active')->label(__('atrium::fields.standard.active')),
        ];
    }
}
