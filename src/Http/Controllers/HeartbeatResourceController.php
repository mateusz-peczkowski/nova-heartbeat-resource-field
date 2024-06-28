<?php

namespace MateuszPeczkowski\NovaHeartbeatResourceField\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Nova;

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
            return response()->json($validationResult['errors'], 400);

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
     * POST /heartbeats
     * Store or Update a heartbeat
     *
     * @param Request $request
     */
    public function storeOrUpdate(Request $request)
    {
        $validationResult = $this->validateRequest($request);

        $authModel = Auth::guard(config('nova-heartbeat-resource-field.heartbeat_guard', (config('nova.guard') ?: null)))->user();

        if (!$authModel) {
            $validationResult['has_errors'] = true;
            $validationResult['errors'][] = __('novaHeartbeatResourceField.errors.userUnauthenticated');
        }

        if ($validationResult['has_errors'] === true)
            return response()->json($validationResult['errors'], 400);

        $model = $validationResult['model'];

        $heartBeatModel = $model
            ->heartbeatResources()
            ->first();

        if ($heartBeatModel) {
            if ($heartBeatModel->created_by === $authModel->id) {
                $heartBeatModel->update([
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                return response()->json([], 403);
            }
        } else {
            $model
                ->heartbeatResources()
                ->create([
                    'created_by' => $authModel->id,
                ]);
        }

        return response()->json();
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
            $validationResult['errors'][] = __('novaHeartbeatResourceField.errors.userUnauthenticated');
        }

        if ($validationResult['has_errors'] === true)
            return response()->json($validationResult['errors'], 400);

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
            $errors[] = __('novaHeartbeatResourceField.errors.resourceIdRequired');

        if (empty($resourceName))
            $errors[] = __('novaHeartbeatResourceField.errors.resourceNameRequired');

        if (!empty($resourceName)) {
            $resourceClass = Nova::resourceForKey($resourceName);

            if (empty($resourceClass)) {
                $errors[] = __('novaHeartbeatResourceField.errors.resourceNameInvalid');
            } else {
                $modelClass = $resourceClass::$model;

                if (method_exists($modelClass, 'trashed')) {
                    $model = $modelClass::withTrashed()->find($resourceId);
                } else {
                    $model = $modelClass::find($resourceId);
                }

                if (empty($model))
                    $errors[] = __('novaHeartbeatResourceField.errors.resourceIdNotFound');

                if (!empty($model) && !method_exists($modelClass, 'heartbeatResources'))
                    $errors[] = __('novaHeartbeatResourceField.errors.resourceIdNotFound');
            }
        }

        return [
            'has_errors' => sizeof($errors) > 0,
            'errors'     => $errors,
            'model'      => isset($model) ? $model : null,
        ];
    }
}
