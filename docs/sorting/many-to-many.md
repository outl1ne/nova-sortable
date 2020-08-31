## Nova Sortable

### Sorting ManyToMany relationships (w/ pivot table)

NB! Sorting is impossible when your pivot table has multiple items with the same ID pairs.

#### Add new columns to Pivot table

Add two new columns to the pivot table: `id` (primary key with auto increment) and `sort_order` (integer).

#### Create Pivot model

```cmd
# Can be created using Laravel's helper command
php artisan make:model -p ArtistTrack
```

### Apply eloquent-sortable to the pivot model

Apply the interface, trait and also overwrite the `buildSortQuery()` method to narrow the sorting scope.

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class ArtistTrack extends Pivot implements Sortable
{
    use SortableTrait;

    public $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    public function buildSortQuery()
    {
        return static::query()
          ->where('track_id', $this->track_id); // As we're sorting Artists belonging to a Track, we're setting this to filter using track_id
    }
}
```

#### Add the Pivot model to the relationship queries

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    public function tracks()
    {
        return $this->belongsToMany(Track::class)
            ->using(ArtistTrack::class);
    }
}
```

```php
// Same applies to Track model
public function artists()
{
    return $this->belongsToMany(Artist::class)
        ->using(ArtistTrack::class);
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

#### Allow or deny sorting on specific resources

Allow sorting only on a specific resource

```php
public $sortable = [
    'only_sort_on' => \App\Nova\Resources\Chapter::class,
];
```

Deny sorting on a list of resources

```php
public $sortable = [
    'dont_sort_on' => [
        \App\Nova\Resources\Comic::class,
    ]
];
```
