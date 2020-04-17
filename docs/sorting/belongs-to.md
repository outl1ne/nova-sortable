## Nova Sortable

### Sorting BelongsToMany relationship (w/ pivot table)

NB! Sorting is impossible when your pivot table has multiple items with the same ID pairs.

First, add a new column for the sort order data to the **pivot** table. Default name is `sort_order`, but it's configurable if you create a new `Pivot` class and add the `$sortable` property to it.

Next, set `sort_on_belongs_to` to `true` on the main model's (not the pivot class') `$sortable = []` array.

```php
public $sortable = [
  'order_column_name' => 'sort_order',
  'sort_when_creating' => true,
  'sort_on_belongs_to' => true,
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
