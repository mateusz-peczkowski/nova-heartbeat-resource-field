<?php

namespace MateuszPeczkowski\NovaHeartbeatResourceField\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use MateuszPeczkowski\NovaHeartbeatResourceField\HeartbeatResourceServiceProvider;
use MateuszPeczkowski\NovaHeartbeatResourceField\Models\HeartbeatResource;

trait HasHeartbeats
{
    /**
     * Get all heartbeats for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     **/
    public function heartbeatResources(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(HeartbeatResourceServiceProvider::getHeartbeatsModel(), 'resource');
    }


    /**
     * Creates a new heartbeat for the model.
     *
     * @return HeartbeatResource
     **/
    public function addHeartbeatResource(): HeartbeatResource
    {
        $user = Auth::guard(config('nova-heartbeat-resource-field.heartbeat_guard', (config('nova.guard') ?: null)))->user();

        return $this->heartbeatResources()->create([
            'created_by' => $user?->id,
        ]);
    }

    /**
     * Updates the heartbeat for the model.
     *
     * @param int $heartbeatResourceId The ID of the heartbeat to update.
     *
     * @return HeartbeatResource
     **/
    public function updateHeartbeatResource(int $heartbeatResourceId): HeartbeatResource
    {
        $heartbeatResource = $this->heartbeatResources()->where('id', '=', $heartbeatResourceId)->firstOrFail();

        $heartbeatResource->update([
            'updated_at' => Carbon::now(),
        ]);

        return $heartbeatResource;
    }

    /**
     * Deletes a heartbeat for the model.
     *
     * @param int $heartbeatResourceId The ID of the heartbeat to delete.
     *
     * @return void
     **/
    public function deleteHeartbeatResource(int $heartbeatResourceId): void
    {
        $this->heartbeatResources()->where('id', '=', $heartbeatResourceId)->delete();
    }
}
