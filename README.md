# Nova Page Manager

This [Laravel Nova](https://nova.laravel.com) package allows you to reorder models in a Nova resource's index view.

## Requirements

- Laravel Nova >= 2.6.0

## Screenshots

TODO

## Installation

Install the package in a Laravel Nova project via Composer:

```bash
# Install package
composer require optimistdigital/nova-sortable
```

Add an order field to the model using Laravel migrations:

```bash
# Add order field
$table->unsignedInteger('sort_order')->nullable();
```

Implement the Spatie's `eloquent-sortable` interface and apply the trait:

```php
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class MyModel extends Eloquent implements Sortable
{
  use SortableTrait;

  public $sortable = [
    'order_column_name' => 'order_column',
    'sort_when_creating' => true,
  ];

  ...
}
```

## Credits

- [Tarvo Reinpalu](https://github.com/Tarpsvo)

## License

Nova page manager is open-sourced software licensed under the [MIT license](LICENSE.md).
