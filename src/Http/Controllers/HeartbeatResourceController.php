<?php

namespace MateuszPeczkowski\NovaHeartbeatResourceField\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Nova;
use MateuszPeczkowski\NovaHeartbeatResourceField\Models\HeartbeatResource;

class HeartbeatResourceController extends Controller
{
    /*
     * GET /heartbeats
     * Return all heartbeats
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $validationResult = $this->validateRequest($request);

        if ($validationResult['has_errors'] === true)
            return response($validationResult['errors'], 400);

        $model = $validationResult['model'];

        return response()->json(
            $model
                ->heartbeatResources()
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->first()
        );
    }

    /*
     * POST /heartbeat
     * Create a new heartbeat
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
    }

    /*
     * PUT /heartbeat/{heartbeatId}
     * Update a heartbeat
     *
     * @param Request $request
     * @param HeartbeatResource $heartbeatResource
     */
    public function update(Request $request, HeartbeatResource $heartbeatResource)
    {

    }

    /*
     * DELETE /heartbeats
     * Delete my heartbeats
     *
     * @param Request $request
     */
    public function delete(Request $request)
    {
        $validationResult = $this->validateRequest($request);

        $authModel = Auth::guard(config('nova-heartbeat-resource-field.heartbeat_guard', (config('nova.guard') ?: null)))->user();

        if (!$authModel) {
            $validationResult['has_errors'] = true;
            $validationResult['errors']['user'] = 'unauthenticated';
        }

        if ($validationResult['has_errors'] === true)
            return response($validationResult['errors'], 400);

        $model = $validationResult['model'];

        $model
            ->heartbeatResources()
            ->where('created_by', $authModel->id)
            ->delete();

        return response()->json();
    }

    //

    private function validateRequest(Request $request)
    {
        $resourceId = $request->get('resourceId');
        $resourceName = $request->get('resourceName');

        $errors = [];

        if (empty($resourceId))
            $errors['resourceId'] = 'required';

        if (empty($resourceName))
            $errors['resourceName'] = 'required';

        if (!empty($resourceName)) {
            $resourceClass = Nova::resourceForKey($resourceName);

            if (empty($resourceClass)) {
                $errors['resourceName'] = 'invalid_name';
            } else {
                $modelClass = $resourceClass::$model;

                if (method_exists($modelClass, 'trashed')) {
                    $model = $modelClass::withTrashed()->find($resourceId);
                } else {
                    $model = $modelClass::find($resourceId);
                }

                if (empty($model))
                    $errors['resourceId'] = 'not_found';

                if (!empty($model) && !method_exists($modelClass, 'heartbeatResources'))
                    $errors['resourceId'] = 'not_found';
            }
        }

        return [
            'has_errors' => sizeof($errors) > 0,
            'errors'     => $errors,
            'model'      => isset($model) ? $model : null,
        ];
    }
}
