<?php

namespace MateuszPeczkowski\NovaHeartbeatResourceField\Models;

use Illuminate\Database\Eloquent\Model;
use MateuszPeczkowski\NovaHeartbeatResourceField\HeartbeatResourceServiceProvider;

class HeartbeatResource extends Model
{
    protected $table = 'nova_heartbeats';

    protected $fillable = [
        'created_by',
        'resource_type',
        'resource_id',
        'updated_at',
    ];

    protected $hidden = [
        'createdBy',
        'resource_type',
        'resource_id'
    ];

    protected $appends = [
        'created_by_name',
        'created_by_avatar_url'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(HeartbeatResourceServiceProvider::getTableName());
    }

    public function resource()
    {
        return $this->morphTo();
    }

    public function providerModel()
    {
        $guard = config('nova-heartbeat-resource-field.heartbeat_guard', (config('nova.guard') ?: null));
        $provider = config('auth.guards.' . $guard . '.provider');

        return config('auth.providers.' . $provider . '.model');
    }

    public function createdBy()
    {
        return $this->belongsTo($this->providerModel(), 'created_by');
    }

    public function getCreatedByNameAttribute()
    {
        $createdBy = $this->createdBy;
        $columnName = config('nova-heartbeat-resource-field.heartbeat_guard_name');

        if (empty($createdBy) || empty($createdBy->$columnName))
            return __('User');

        return $createdBy->$columnName;
    }

    public function getCreatedByAvatarUrlAttribute()
    {
        $createdBy = $this->createdBy;

        if (empty($createdBy))
            return __('User');

        $avatarFnOrCallable = config('nova-heartbeat-resource-field.heartbeat_avatar_url');

        if ($avatarFnOrCallable) {
            if (is_callable($avatarFnOrCallable))
                return call_user_func($avatarFnOrCallable, $createdBy);

            return $createdBy->$avatarFnOrCallable ?? null;
        }

        $columnName = config('nova-heartbeat-resource-field.heartbeat_guard_email');

        if (empty($createdBy->$columnName))
            return null;

        return 'https://www.gravatar.com/avatar/' . md5(mb_strtolower($createdBy->$columnName)) . '?s=200';
    }
}
