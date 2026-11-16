# Atrium Brands

A directory of brands for [Atrium](https://github.com/AlveBy/atrium): name, code, logo, brand colour, website and description. Brands have no pages of their own. Other modules link to them: a product in [`alveby/atrium-catalog`](https://github.com/AlveBy/atrium/tree/main/packages/catalog) shows its brand's name.

## Install

```bash
composer require alveby/atrium-brands
php artisan atrium:module:install alveby/atrium-brands
```

Add `--demo` to fill it with three brands. The catalog works with or without this module. Install it later, and the "Brand" field appears in the product form.

## Link to a brand from your own module

```php
use Atrium\Admin\Modules\Soft;
use Atrium\Admin\Traits\HasSoftRelations;
use Atrium\Brands\Models\Brand;

public function brand(): BelongsTo
{
    return $this->softBelongsTo(Brand::class, 'brand_id');
}

// in getAdministrationFields()
...Soft::when(Brand::class, fn (): array => [
    BelongsToField::make('brand_id')->label('Brand')->model(Brand::class),
]),
```

See "Links to other modules" in [docs/modules.md](https://github.com/AlveBy/atrium/blob/main/docs/modules.md).

## Contributing

This repository is a read-only copy of `packages/brands` in [AlveBy/atrium](https://github.com/AlveBy/atrium). Issues and pull requests go there.

## License

MIT
