# Nova Sortable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/optimistdigital/nova-sortable.svg?style=flat-square)](https://packagist.org/packages/optimistdigital/nova-sortable)
[![Total Downloads](https://img.shields.io/packagist/dt/optimistdigital/nova-sortable.svg?style=flat-square)](https://packagist.org/packages/optimistdigital/nova-sortable)

This [Laravel Nova](https://nova.laravel.com) package allows you to reorder models in a Nova resource's index view using drag & drop.

Uses Spatie's [eloquent-sortable](https://github.com/spatie/eloquent-sortable) under the hood.

## Requirements

- Laravel Nova >= 2.10.0
- For Laravel Nova < 2.10.0 use `nova-sortable` version `1.1.3`

## Features

- Drag & drop reorder within one page of resources
- Move to start and end arrows (makes item first/last)
- Localization
- Everything from [eloquent-sortable](https://github.com/spatie/eloquent-sortable)

## Screenshots

![Sortable](./docs/sortable.gif)

## Installation

Install the package in a Laravel Nova project via Composer:

```bash
# Install package
composer require optimistdigital/nova-sortable
```

## Usage

Add an order field to the model using Laravel migrations:

```bash
# Add order field with default value
Schema::table('some_model', function (Blueprint $table) {
  $table->integer('sort_order');
});
DB::statement('UPDATE some_model SET sort_order = id');
```

Implement the Spatie's `eloquent-sortable` interface and apply the trait:

```php
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class MyModel extends Eloquent implements Sortable
{
  use SortableTrait;

  public $sortable = [
    'order_column_name' => 'sort_order',
    'sort_when_creating' => true,
  ];

  ...
}
```

Apply `HasSortableRows` trait from this package on the Resource:

```php
use OptimistDigital\NovaSortable\Traits\HasSortableRows;

class MyResource extends Resource
{
  use HasSortableRows;

  ...
}
```

## Localization

The translation file(s) can be published by using the following publish command:

```bash
php artisan vendor:publish --provider="OptimistDigital\NovaSortable\ToolServiceProvider" --tag="translations"
```

You can add your translations to `resources/lang/vendor/nova-sortable/` by creating a new translations file with the locale name (ie `et.json`) and copying the JSON from the existing `en.json`.

## Sorting BelongsToMany relatioship (w/ pivot table)

NB! Sorting is impossible when your pivot table has multiple items with the same ID pairs.

First, add a new column for the sort order data to the pivot table. Default name is `sort_order`, but it's configurable if you create a new `Pivot` class and add the `$sortable` property to it.

Next, set `sort_on_pivot` to `true` on the main model's (not the pivot class') `$sortable = []` array.

```
public $sortable = [
  'order_column_name' => 'sort_order',
  'sort_when_creating' => true,
  'sort_on_pivot' => true,
];
```

Finally, add sorting to the pivot query manually. On the parent model (on which the pivots are displayed), add `orderBy()` to the pivot query definition like so:

```
public function products()
{
  return $this->belongsToMany(Product::class, 'order_product')
    ->withPivot(OrderProduct::getPivotFields())
    ->using(OrderProduct::class)
    ->orderBy('order_product.sort_order'); // The `order_product` pivot table name prefix is required!
}
```

## Credits

- [Tarvo Reinpalu](https://github.com/Tarpsvo)

## License

Nova Sortable is open-sourced software licensed under the [MIT license](LICENSE.md).
