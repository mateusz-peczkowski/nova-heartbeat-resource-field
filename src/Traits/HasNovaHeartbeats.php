<?php

namespace MateuszPeczkowski\NovaHeartbeatResourceField\Traits;

use Illuminate\Http\Request;

trait HasNovaHeartbeats
{
    public function authorizedToUpdate(Request $request)
    {
        $authorized = parent::authorizedToUpdate($request);

        if ($authorized && $this->model()->heartbeatResources()->count() > 0)
            $authorized = false;

        return $authorized;
    }

    public function authorizedToDelete(Request $request)
    {
        $authorized = parent::authorizedToDelete($request);

        if ($authorized && $this->model()->heartbeatResources()->count() > 0)
            $authorized = false;

        return $authorized;
    }

    public function authorizedToForceDelete(Request $request)
    {
        $authorized = parent::authorizedToForceDelete($request);

        if ($authorized && $this->model()->heartbeatResources()->count() > 0)
            $authorized = false;

        return $authorized;
    }
}
