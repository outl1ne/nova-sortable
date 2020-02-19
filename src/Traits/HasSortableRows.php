<?php

namespace OptimistDigital\NovaSortable\Traits;

use Laravel\Nova\Http\Requests\NovaRequest;

trait HasSortableRows
{
    public function serializeForIndex(NovaRequest $request, $fields = null)
    {
        $sortable  = $request->viaRelationship()
            ? $request->newResource()->resource->sortable['sort_on_pivot'] ?? false
            : true;

        return array_merge(parent::serializeForIndex($request, $fields), [
            'sortable' => $sortable,
        ]);
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        if (empty($request->get('orderBy')) && !$request->viaRelationship()) {
            $query->getQuery()->orders = [];
            $model = (new static::$model);
            $orderColumn = !empty($model->sortable['order_column_name']) ? $model->sortable['order_column_name'] : 'sort_order';
            return $query->orderBy($orderColumn);
        }

        return $query;
    }
}
