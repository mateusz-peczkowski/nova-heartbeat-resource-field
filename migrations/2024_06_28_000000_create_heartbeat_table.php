<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use MateuszPeczkowski\NovaHeartbeatResourceField\HeartbeatResourceServiceProvider;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(HeartbeatResourceServiceProvider::getTableName(), function (Blueprint $table) {
            $table->id();

            $table->bigInteger('created_by')->nullable();
            $table->morphs('resource');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(HeartbeatResourceServiceProvider::getTableName());
    }
};
