<?php

namespace OptimistDigital\NovaSortable\Http\Controllers;

use Laravel\Nova\Nova;
use \Illuminate\Http\Request;

class SortableController
{
    public function updateOrder(Request $request)
    {
        $resourceIds = $request->get('resourceIds');
        $resourceName = $request->get('resourceName');
        $viaResource = $request->get('viaResource');
        $viaResourceId = $request->get('viaResourceId');
        $viaRelationship = $request->get('viaRelationship');
        $relationshipType = $request->get('relationshipType');

        if (empty($resourceIds)) return response()->json(['resourceIds' => 'required'], 400);
        if (empty($resourceName)) return response()->json(['resourceName' => 'required'], 400);


        // Relationship sorting
        if (isset($viaResource)) {

            $resourceClass = Nova::resourceForKey($viaResource);
            if (empty($resourceClass)) return response()->json(['resourceName' => 'invalid'], 400);

            $modelClass = $resourceClass::$model;
            $model = $modelClass::find($viaResourceId);
            $relatedModels = $model->{$viaRelationship}()->findMany($resourceIds);
            if ($relatedModels->count() !== sizeof($resourceIds)) return response()->json(['resourceIds' => 'invalid'], 400);

            if ($relationshipType === 'belongsToMany') {
                $relatedModel = $relatedModels->first()->pivot;
                $orderColumnName = !empty($relatedModel->sortable['order_column_name']) ? $relatedModel->sortable['order_column_name'] : 'sort_order';


                // Sort orderColumn values
                foreach ($relatedModels as $i => $model) {
                    $model->pivot->{$orderColumnName} = array_search($model->id, $resourceIds);
                    $model->pivot->save();
                }
            } else if ($relationshipType === 'hasMany') {
                $relatedModel = $relatedModels->first();
                $orderColumnName = !empty($relatedModel->sortable['order_column_name']) ? $relatedModel->sortable['order_column_name'] : 'sort_order';

                // Sort orderColumn values
                $sortedOrder = $relatedModels->pluck($orderColumnName)->sort()->values();
                foreach ($resourceIds as $i => $id) {
                    $_model = $relatedModels->firstWhere('id', $id);
                    $_model->{$orderColumnName} = $sortedOrder[$i];
                    $_model->save();
                }
            }

            return response('', 204);
        }

        // Regular
        $resourceClass = Nova::resourceForKey($resourceName);
        if (empty($resourceClass)) return response()->json(['resourceName' => 'invalid'], 400);

        $modelClass = $resourceClass::$model;
        $models = $modelClass::whereIn('id', $resourceIds)->get();
        if ($models->count() !== sizeof($resourceIds)) return response()->json(['resourceIds' => 'invalid'], 400);

        $model = $models->first();
        $orderColumnName = !empty($model->sortable['order_column_name']) ? $model->sortable['order_column_name'] : 'sort_order';

        // Sort orderColumn values
        $sortedOrder = $models->pluck($orderColumnName)->sort()->values();
        foreach ($resourceIds as $i => $id) {
            $_model = $models->firstWhere('id', $id);
            $_model->{$orderColumnName} = $sortedOrder[$i];
            $_model->save();
        }

        return response('', 204);
    }

    public function moveToStart(Request $request)
    {
        $validationResult = $this->validateRequest($request);
        if ($validationResult['has_errors'] === true) return response()->json($validationResult['errors'], 400);
        $model = $validationResult['model'];
        $model->moveToStart();
        return response('', 204);
    }

    public function moveToEnd(Request $request)
    {
        $validationResult = $this->validateRequest($request);
        if ($validationResult['has_errors'] === true) return response()->json($validationResult['errors'], 400);
        $model = $validationResult['model'];
        $model->moveToEnd();
        return response('', 204);
    }

    protected function validateRequest(Request $request)
    {
        $resourceId = $request->get('resourceId');
        $resourceName = $request->get('resourceName');

        $errors = [];
        if (empty($resourceId)) $errors['resourceId'] = 'required';
        if (empty($resourceName)) $errors['resourceName'] = 'required';
        if (!empty($resourceName)) {
            $resourceClass = Nova::resourceForKey($resourceName);
            if (empty($resourceClass)) $errors['resourceName'] = 'invalid_name';
            else {
                $modelClass = $resourceClass::$model;
                $model = $modelClass::find($resourceId);
                if (empty($model)) $errors['resourceId'] = 'not_found';
            }
        }

        return [
            'has_errors' => sizeof($errors) > 0,
            'errors' => $errors,
            'model' => isset($model) ? $model : null,
        ];
    }
}
