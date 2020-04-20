<?php

namespace OptimistDigital\NovaSortable\Traits;

use Laravel\Nova\Http\Requests\NovaRequest;

trait HasSortableRows
{
    public function serializeForIndex(NovaRequest $request, $fields = null)
    {
        $sortable = $request->newResource()->resource->sortable ?? false;
        $sortOnBelongsTo = $sortable['sort_on_belongs_to'] ?? false;
        $sortOnHasMany = $sortable['sort_on_has_many'] ?? false;

        return array_merge(parent::serializeForIndex($request, $fields), [
            'sortable' => $sortable,
            'sort_on_index' => !$sortOnHasMany && !$sortOnBelongsTo,
            'sort_on_has_many' => $sortOnHasMany,
            'sort_on_belongs_to' => $sortOnBelongsTo,
        ]);
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $sortable = $request->newResource()->resource->sortable ?? false;
        $sortOnBelongsTo = $sortable['sort_on_belongs_to'] ?? false;
        $sortOnHasMany = $sortable['sort_on_has_many'] ?? false;

        if (empty($request->get('orderBy')) && $sortable && (!$sortOnBelongsTo && !$sortOnHasMany)) {
            $query->getQuery()->orders = [];
            $model = (new static::$model);
            $orderColumn = !empty($model->sortable['order_column_name']) ? $model->sortable['order_column_name'] : 'sort_order';
            return $query->orderBy($orderColumn);
        }

        return $query;
    }
}
