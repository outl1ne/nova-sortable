# Nova Sortable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/optimistdigital/nova-sortable.svg?style=flat-square)](https://packagist.org/packages/optimistdigital/nova-sortable)
[![Total Downloads](https://img.shields.io/packagist/dt/optimistdigital/nova-sortable.svg?style=flat-square)](https://packagist.org/packages/optimistdigital/nova-sortable)

This [Laravel Nova](https://nova.laravel.com) package allows you to reorder models in a Nova resource's index view using drag & drop.

Uses Spatie's [eloquent-sortable](https://github.com/spatie/eloquent-sortable) under the hood.

## Requirements

- Laravel Nova >= 2.10.0

## Features

- Drag & drop reorder (on either Index view or HasMany view)
- BelongsTo/MorphsTo reorder support w/ pivot tables
- Move to start and end arrows (makes item first/last)
- Everything from [eloquent-sortable](https://github.com/spatie/eloquent-sortable)
- Localization

## Screenshots

![Sortable](./docs/sortable.gif)

## Installation

Install the package in a Laravel Nova project via Composer:

```bash
# Install package
composer require optimistdigital/nova-sortable
```

## Usage

### Create migration

Add an order field to the model using Laravel migrations:

```php
// Add order column to the model
Schema::table('some_model', function (Blueprint $table) {
  $table->integer('sort_order');
});

// Set default sort order (just copy ID to sort order)
DB::statement('UPDATE some_model SET sort_order = id');
```

### Implement eloquent-sortable

Implement the Spatie's `eloquent-sortable` interface and apply the trait:

```php
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class SomeModel extends Eloquent implements Sortable
{
  use SortableTrait;

  public $sortable = [
    'order_column_name' => 'sort_order',
    'sort_when_creating' => true,
  ];

  ...
}
```

### Apply HasSortableRows to Nova resource

Apply `HasSortableRows` trait from this package on the Resource:

```php
use OptimistDigital\NovaSortable\Traits\HasSortableRows;

class MyResource extends Resource
{
  use HasSortableRows;

  ...
}
```

NB! This overrides the `indexQuery()` method.

### Disallowing sorting on a per-request basis

You can disable sorting on a per-request basis by overriding the `canSort()` on the Resource method like so:

```php
public static function canSort(NovaRequest $request)
{
  // Do whatever here, ie:
  // return user()->isAdmin();
  return true;
}
```

## Sorting on HasMany relationship

**NB!** The resource can only be sorted on **either** the Index view **or** the HasMany list view, but not both!

Sorting on HasMany is simple. Add `'sort_on_has_many' => true` to the `$sortable` array on the model. Like so:

```php
public $sortable = [
  'order_column_name' => 'sort_order',
  'sort_when_creating' => true,
  'sort_on_has_many' => true,
];
```

## Sorting on ManyToMany relationships

Sorting on BelongsToMany and MorphToMany relationships is available, but requires special steps.

See the documentation here: [Sorting ManyToMany relationships (w/ pivot table)](docs/sorting/many-to-many.md).

## Localization

The translation file(s) can be published by using the following publish command:

```bash
php artisan vendor:publish --provider="OptimistDigital\NovaSortable\ToolServiceProvider" --tag="translations"
```

You can add your translations to `resources/lang/vendor/nova-sortable/` by creating a new translations file with the locale name (ie `et.json`) and copying the JSON from the existing `en.json`.

## Credits

- [Tarvo Reinpalu](https://github.com/Tarpsvo)

## License

Nova Sortable is open-sourced software licensed under the [MIT license](LICENSE.md).
