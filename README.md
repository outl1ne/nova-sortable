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

## Sorting BelongsToMany relationship (w/ pivot table)

NB! Sorting is impossible when your pivot table has multiple items with the same ID pairs.

First, add a new column for the sort order data to the pivot table. Default name is `sort_order`, but it's configurable if you create a new `Pivot` class and add the `$sortable` property to it.

Next, set `sort_on_pivot` to `true` on the main model's (not the pivot class') `$sortable = []` array.

```php
public $sortable = [
  'order_column_name' => 'sort_order',
  'sort_when_creating' => true,
  'sort_on_pivot' => true,
];
```

Finally, add sorting to the pivot query manually. On the parent model (on which the pivots are displayed), add `orderBy()` to the pivot query definition like so:

```php
public function products()
{
  return $this->belongsToMany(Product::class, 'order_product')
    ->withPivot(OrderProduct::getPivotFields())
    ->using(OrderProduct::class)
    ->orderBy('order_product.sort_order'); // The `order_product` pivot table name prefix is required!
}
```

## Displaying items according to their BelongsTo parent's order

Let's imagine we have `User` and `UserGroup` resources, where `UserGroup` is sorted by nova-sortable.
You may want to have your users listed in the same order as the order of groups they belong to. So,
users from the first group, then users from the second group, etc. You can change the default ordering for
a Nova resource by adding `applyOrderings` function to your resource class like so:

```php
protected static function applyOrderings($query, array $orderings)
{
  if (empty($orderings)) {
    $query->join('user_groups', 'users.user_group_id', '=', 'user_groups.id');
    $query->select('users.*');
    $orderings = [
      'user_groups.priority' => 'asc',
      // 'users.name' => 'asc', // to make users in each user group get sorted by their name
      // 'users.email' => 'asc', // to make users in the same group and with the same name get
                                 // sorted by their email
      // etc.
    ];
  }
  return parent::applyOrderings($query, $orderings);
}
```

This will apply ordering to the index page and index fragments (e.g. `HasMany`). Please note that
`BelongsTo` field always sorts all items alphabetically (due to `sortBy('display')` in
`AssociatableController`).

You may alternatively define ordering in your Eloquent model:

```php
protected static function boot()
{
  parent::boot();
  static::addGlobalScope('ordered', function (Builder $query) {
    $query->join('user_groups', 'users.user_group_id', '=', 'user_groups.id');
    $query->select('users.*');
    $query->orderBy('user_groups.priority', 'asc');
    // $query->orderBy('users.name', 'asc');
    // $query->orderBy('users.login', 'asc');
    // etc.
  });
}
```

and then disable the default Nova order-by-recent behavior in your resource with:

```php
protected static function applyOrderings($query, array $orderings)
{
  if (empty($orderings)) return $query;
  return parent::applyOrderings($query, $orderings);
}
```

## Credits

- [Tarvo Reinpalu](https://github.com/Tarpsvo)

## License

Nova Sortable is open-sourced software licensed under the [MIT license](LICENSE.md).
