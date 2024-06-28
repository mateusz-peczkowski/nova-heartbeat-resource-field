<?php

namespace MateuszPeczkowski\NovaHeartbeatResourceField\Commands;

use Illuminate\Console\Command;
use MateuszPeczkowski\NovaHeartbeatResourceField\HeartbeatResourceServiceProvider;

class ClearExpiredHeartbeats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'heartbeat:clear-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear expired heartbeats from the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        (new (HeartbeatResourceServiceProvider::getHeartbeatsModel()))
            ->where('updated_at', '<', now()->subMilliseconds(config('nova-heartbeat-resource-field.heartbeat_timeout')))
            ->delete();
    }
}