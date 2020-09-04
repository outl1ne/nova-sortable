<?php

namespace OptimistDigital\NovaSortable\Traits;

use Laravel\Nova\Http\Requests\NovaRequest;

trait HasSortableRows
{
    public static function getSortability(NovaRequest $request)
    {
        $model = null;

        try {
            $resource = isset($request->resourceId) ? $request->findResourceOrFail() : $request->newResource();
        } catch (\Exception $e) {
            return null;
        }

        $model = $resource->resource ?? null;
        $sortable = $model->sortable ?? false;
        $sortOnBelongsTo = $resource->disableSortOnIndex ?? false;
        $sortOnHasMany = $sortable['sort_on_has_many'] ?? false;

        if ($request->viaManyToMany()) {
            $relationshipQuery = $request->findParentModel()->{$request->viaRelationship}();

            if (isset($request->resourceId)) {
                $tempModel = $relationshipQuery->first()->pivot ?? null;
                $model = !empty($tempModel) ?
                    $relationshipQuery->withPivot($tempModel->getKeyName(), $tempModel->sortable['order_column_name'])->find($request->resourceId)->pivot
                    : null;
            } else {
                $model = $relationshipQuery->first()->pivot ?? null;
            }

            $sortable = $model->sortable ?? false;
            $sortOnBelongsTo = !empty($sortable);

            // Check for `only_sort_on` and `dont_sort_on`
            $hasOnlySortOn = is_array($sortable) && key_exists('only_sort_on', $sortable);
            $onlySortOnMatches = $hasOnlySortOn && $request->viaResource() === $sortable['only_sort_on'];

            // Disable when `only_sort_on` does not match
            if ($hasOnlySortOn && !$onlySortOnMatches) $sortOnHasMany = $sortOnBelongsTo = false;

            // Disable sorting on `dont_sort_on` models
            if (is_array($sortable) && key_exists('dont_sort_on', $sortable)) {
                foreach ($sortable['dont_sort_on'] as $item) {
                    if ($item === $request->viaResource()) {
                        $sortOnBelongsTo = $sortOnHasMany = false;
                        break;
                    }
                }
            }
        }

        return (object) [
            'model' => $model,
            'sortable' => $sortable,
            'sortOnBelongsTo' => $sortOnBelongsTo,
            'sortOnHasMany' => $sortOnHasMany,
        ];
    }

    public function serializeForIndex(NovaRequest $request, $fields = null)
    {
        $sortability = static::getSortability($request);

        return array_merge(parent::serializeForIndex($request, $fields), [
            'sortable' => $sortability->sortable,
            'sort_on_index' => $sortability->sortable && !$sortability->sortOnHasMany && !$sortability->sortOnBelongsTo,
            'sort_on_has_many' => $sortability->sortable && $sortability->sortOnHasMany,
            'sort_on_belongs_to' => $sortability->sortable && $sortability->sortOnBelongsTo,
        ]);
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $sortability = static::getSortability($request);

        if (!empty($sortability)) {
            // Make sure we are querying the same table, which might not be the case
            // in some complicated relationship views
            if ($request->viaManyToMany()) {
                $tempModel = $request->findParentModel()->{$request->viaRelationship}()->first();
                $sortabilityTable = !empty($tempModel) ? $tempModel->getTable() : null;
            } else {
                $sortabilityTable = $sortability->model->getTable();
            }

            if ($query->getQuery()->from === $sortabilityTable) {
                $shouldSort = true;
                if (empty($sortability->sortable)) $shouldSort = false;
                if ($sortability->sortOnBelongsTo && empty($request->viaResource())) $shouldSort = false;
                if ($sortability->sortOnHasMany && empty($request->viaResource())) $shouldSort = false;

                if (empty($request->get('orderBy')) && $shouldSort) {
                    $query->getQuery()->orders = [];
                    $orderColumn = !empty($sortability->sortable['order_column_name']) ? $sortability->sortable['order_column_name'] : 'sort_order';
                    return $query->orderBy($orderColumn);
                }
            }
        }

        return $query;
    }
}
