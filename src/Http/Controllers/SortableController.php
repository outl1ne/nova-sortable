<?php

namespace OptimistDigital\NovaSortable\Http\Controllers;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
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
            if ($relatedModels->count() !== count($resourceIds)) return response()->json(['resourceIds' => 'invalid'], 400);

            // BelongsToMany
            if ($relationshipType === 'belongsToMany' || $relationshipType === 'morphToMany') {
                $relatedModel = $relatedModels->first()->pivot;
            } else if ($relationshipType === 'hasMany') {
                $relatedModel = $relatedModels->first();
            }

            if (!empty($relatedModel)) {
                $orderColumnName = $relatedModel->determineOrderColumnName();
                $relatedKeyName = $relatedModel->getKeyName();

                // Order column is not always loaded in ManyToMany relationships
                $startOrder = $model->{$viaRelationship}()->whereKey($resourceIds)->min($orderColumnName) ?: 1;

                foreach ($resourceIds as $id) {
                    $_model = $relatedModels->firstWhere($relatedKeyName, $id);
                    if ($relationshipType === 'belongsToMany' || $relationshipType === 'morphToMany') {
                        $_model->pivot->{$orderColumnName} = $startOrder++;
                        $_model->pivot->save();
                    } else {
                        $_model->{$orderColumnName} = $startOrder++;
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
        if (method_exists($modelClass, 'withTrashed')) {
            $models = $modelClass::withTrashed()->findMany($resourceIds);
        } else {
            $models = $modelClass::findMany($resourceIds);
        }
        if ($models->count() !== sizeof($resourceIds)) return response()->json(['resourceIds' => 'invalid'], 400);

        $model = $models->first();
        $modelKeyName = $model->getKeyName();
        $orderColumnName = $model->determineOrderColumnName();

        // Get starting order value
        $startOrder = $models->min($orderColumnName) ?: 1;
        foreach ($resourceIds as $id) {
            $_model = $models->firstWhere($modelKeyName, $id);
            $_model->{$orderColumnName} = $startOrder++;
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
