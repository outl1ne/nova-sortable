<?php

namespace OptimistDigital\NovaSortable\Http\Controllers;

use Laravel\Nova\Nova;
use Laravel\Nova\Http\Requests\NovaRequest;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;

class SortableController
{
    public function updateOrder(NovaRequest $request)
    {
        $validationResult = $this->validateRequest($request);
        $model = $validationResult->model;

        $resourceName = $request->route('resource');
        $resourceIds = $request->input('resourceIds');
        $viaResource = $request->input('viaResource');
        $viaResourceId = $request->input('viaResourceId');
        $viaRelationship = $request->input('viaRelationship');
        $relationshipType = $request->input('relationshipType');

        // Relationship sorting
        if (!empty($viaResource)) {
            $resourceClass = Nova::resourceForKey($viaResource);
            if (empty($resourceClass)) return response()->json(['resourceName' => 'invalid'], 400);

            $modelClass = $resourceClass::$model;
            $model = $modelClass::find($viaResourceId);
            $relatedModels = $model->{$viaRelationship}()->findMany($resourceIds);
            if ($relatedModels->count() !== sizeof($resourceIds)) return response()->json(['resourceIds' => 'invalid'], 400);

            // BelongsToMany
            if ($relationshipType === 'belongsToMany' || $relationshipType === 'morphToMany') {
                $relatedModel = $relatedModels->first()->pivot;
            } else if ($relationshipType === 'hasMany') {
                $relatedModel = $relatedModels->first();
            }

            if (!empty($relatedModel)) {
                $orderColumnName = !empty($relatedModel->sortable['order_column_name']) ? $relatedModel->sortable['order_column_name'] : 'sort_order';
                $relatedKeyName = $relatedModel->getKeyName();

                // Sort orderColumn values
                if ($relationshipType === 'belongsToMany' || $relationshipType === 'morphToMany') {
                    $sortedOrder = $relatedModels->pluck('pivot')->pluck($orderColumnName)->sort()->values();
                } else {
                    $sortedOrder = $relatedModels->pluck($orderColumnName)->sort()->values();
                }

                $previousSortOrderNr = null;
                foreach ($resourceIds as $i => $id) {
                    $_model = $relatedModels->firstWhere($relatedKeyName, $id);
                    $sortOrderNr = $sortedOrder[$i] ?? ((int) $previousSortOrderNr) + 1;
                    if ($sortOrderNr === $previousSortOrderNr) $sortOrderNr += 1;
                    $previousSortOrderNr = $sortOrderNr;

                    if ($relationshipType === 'belongsToMany' || $relationshipType === 'morphToMany') {
                        $_model->pivot->{$orderColumnName} = $sortOrderNr;
                        $_model->pivot->save();
                    } else {
                        $_model->{$orderColumnName} = $sortOrderNr;
                        $_model->save();
                    }
                }
            }

            return response('', 204);
        }

        // Regular ordering
        $resourceClass = Nova::resourceForKey($resourceName);
        if (empty($resourceClass)) return response()->json(['resourceName' => 'invalid'], 400);

        $modelClass = $resourceClass::$model;
        $models = $modelClass::make()->buildSortQuery()->findMany($resourceIds);
        if ($models->count() !== sizeof($resourceIds)) return response()->json(['resourceIds' => 'invalid'], 400);

        $model = $models->first();
        $modelKeyName = $model->getKeyName();
        $orderColumnName = !empty($model->sortable['order_column_name']) ? $model->sortable['order_column_name'] : 'sort_order';

        // Sort orderColumn values
        $sortedOrder = $models->pluck($orderColumnName)->sort()->values();
        foreach ($resourceIds as $i => $id) {
            $_model = $models->firstWhere($modelKeyName, $id);
            $_model->{$orderColumnName} = $sortedOrder[$i];
            $_model->save();
        }

        return response('', 204);
    }

    public function moveToStart(NovaRequest $request)
    {
        $validationResult = $this->validateRequest($request);
        $validationResult->model->moveToStart();
        return response('', 204);
    }

    public function moveToEnd(NovaRequest $request)
    {
        $validationResult = $this->validateRequest($request);
        $validationResult->model->moveToEnd();
        return response('', 204);
    }

    protected function validateRequest(NovaRequest $request)
    {
        $request->validate([
            'resourceId' => 'present',
            'resourceIds' => 'required_if:resourceId,null',
            'relatedResource' => 'present',
            'relationshipType' => 'present',
            'viaRelationship' => 'present',
            'viaResource' => 'present',
            'viaResourceId' => 'present',
        ]);

        return HasSortableRows::getSortability($request);
    }
}
