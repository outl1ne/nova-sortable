<?php

namespace OptimistDigital\NovaSortable\Traits;

use Laravel\Nova\Http\Requests\NovaRequest;

trait HasSortableRows
{
    public function serializeForIndex(NovaRequest $request, $fields = null)
    {
        return array_merge(parent::serializeForIndex($request, $fields), [
            'sortable' => true,
        ]);
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->when(empty($request->get('orderBy')), function ($q) {
            $q->getQuery()->orders = [];
            $model = (new static::$model);
            $orderColumn = !empty($model->sortable['order_column_name']) ? $model->sortable['order_column_name'] : 'order_column';
            return $q->orderBy($orderColumn);
        });

        return $query;
    }
}
