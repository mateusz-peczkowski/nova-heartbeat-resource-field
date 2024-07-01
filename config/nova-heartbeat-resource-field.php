<?php

return [
    /*
    \    Table name
    \    The table name used for storing the heartbeats.
    \    default: nova_heartbeats
    */
    'table_name'            => 'nova_heartbeats',

    /*
    \    Heartbeat model
    \    The model used for storing the heartbeats.
    */
    'heartbeat_model'       => \MateuszPeczkowski\NovaHeartbeatResourceField\Models\HeartbeatResource::class,

    /*
     \ Interval
     \ The interval in milliseconds for how often to update the heartbeat.
     */
    'heartbeat_interval'    => env('NOVA_HEARTBEAT_INTERVAL', 5 * 1000), // 5 seconds

    /*
     \ Timeout
     \ The timeout in milliseconds for when a heartbeat is considered stale.
     */
    'heartbeat_timeout'     => env('NOVA_HEARTBEAT_TIMEOUT', 60 * 1000), // 1 minute

    /*
     \ Guard
     \ The guard used for authenticating the user.
     */
    'heartbeat_guard'       => 'web',

    /*
     \ Guard model "name" column
     \ The column used for the name of the user.
     */
    'heartbeat_guard_name'  => 'name',

    /*
     \ Guard model "email" column
     \ The column used for the email of the user.
     */
    'heartbeat_guard_email' => 'email',

    /*
     \ Guard model "avatar"
     \ Callable or the model attribute that accesses the avatar URL.
     \
     \ For example:
     \ 'get_avatar_url' => fn($user) => $user->getAvatarUrl();
     \ 'get_avatar_url' => 'avatarUrl';
     \
     \ This assumes that you have the following on your User model: public function getAvatarUrlAttribute() {}
     */
    'heartbeat_avatar_url'  => null,
];
