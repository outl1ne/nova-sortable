<?php

namespace OptimistDigital\NovaSortable\Traits;

use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\EloquentSortable\SortableTrait;

trait HasSortableRows
{
    public static function canSort(NovaRequest $request, $resource)
    {
        return true;
    }

    public static function getSortability(NovaRequest $request, $resource = null)
    {
        $model = null;

        try {
            if ($resource === null) {
                $resource = isset($request->resourceId) ? $request->findResourceOrFail() : $request->newResource();
            }
        } catch (\Exception $e) {
            return null;
        }

        $model = $resource->resource ?? $resource ?? null;
        if (!$model || !self::canSort($request, $model)) {
            return (object)['canSort' => false];
        }

        $sortable = self::getSortabilityConfiguration($model);
        $sortOnBelongsTo = $resource->disableSortOnIndex ?? false;
        $sortOnHasMany = $sortable['sort_on_has_many'] ?? false;

        if ($request->viaManyToMany()) {
            $relationshipQuery = $request->findParentModel()->{$request->viaRelationship}();

            if (isset($request->resourceId)) {
                $tempModel = $relationshipQuery->first()->pivot ?? null;
                $model = !empty($tempModel) ?
                    $relationshipQuery->withPivot($tempModel->getKeyName(), $tempModel->determineOrderColumnName())->find($request->resourceId)->pivot
                    : null;
            } else {
                $model = $relationshipQuery->first()->pivot ?? null;
            }

            if (!$model || !self::canSort($request, $model)) {
                return (object)['canSort' => false];
            }

            $sortable = self::getSortabilityConfiguration($model);
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
        $sortability = static::getSortability($request, $this->resource);

        if (is_null($sortability)) {
            return parent::serializeForIndex($request, $fields);
        }

        $sortabilityData = ['has_sortable_trait' => true];
        $sortabilityData = array_merge($sortabilityData, $sortability->sortable ?? false ? [
            'sortable' => $sortability->sortable,
            'sort_on_index' => $sortability->sortable && !$sortability->sortOnHasMany && !$sortability->sortOnBelongsTo,
            'sort_on_has_many' => $sortability->sortable && $sortability->sortOnHasMany,
            'sort_on_belongs_to' => $sortability->sortable && $sortability->sortOnBelongsTo,
        ] : ['sort_not_allowed' => !($sortability->canSort ?? false)]);

        return array_merge(parent::serializeForIndex($request, $fields), $sortabilityData);
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $sortability = static::getSortability($request);

        if (!empty($sortability->model)) {
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
                    return $query->orderBy($sortability->model->determineOrderColumnName());
                }
            }
        }

        return $query;
    }

    /**
     * Get model sortability configuration
     */
    public static function getSortabilityConfiguration($model): ?array
    {
        if (is_null($model)) return null;

        // Check if spatie trait is in the model.
        if (! in_array(SortableTrait::class, class_uses_recursive($model))) {
            return null;
        }

        // Get spatie defaut configuration.
        $defaultConfiguration = config('eloquent-sortable', []);

        // If model does not have sortable configuration return the default.
        if (!isset($model->sortable)) return $defaultConfiguration;

        return array_merge($defaultConfiguration, $model->sortable);
    }
}
