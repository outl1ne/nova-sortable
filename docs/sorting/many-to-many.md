## Nova Sortable

### Sorting ManyToMany relationships (w/ pivot table)

NB! Sorting is impossible when your pivot table has multiple items with the same ID pairs.

#### Create Pivot model

```cmd
# Can be created using Laravel's helper command
php artisan make:model -p ArtistTrack
```

### Apply eloquent-sortable to the pivot model

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class ArtistTrack extends Pivot implements Sortable
{
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];
}
```

#### Add the HasSortableManyToManyRows trait

Add the `HasSortableManyToManyRows` trait to the Resource you want to sort on BelongsTo (in this example, can be either `Artist` or `Track`), but let's go for `Artist`.

```php
use OptimistDigital\NovaSortable\Traits\HasSortableManyToManyRows;

class Artist extends Resource
{
    use HasSortableManyToManyRows;

    // ...
}
```
