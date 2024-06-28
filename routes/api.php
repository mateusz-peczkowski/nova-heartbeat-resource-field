<?php

use Illuminate\Support\Facades\Route;

Route::get('/heartbeats', 'HeartbeatResourceController@index');
Route::post('/heartbeat', 'HeartbeatResourceController@store');
Route::put('/heartbeat/{heartbeatId}', 'HeartbeatResourceController@update');
Route::delete('/heartbeats', 'HeartbeatResourceController@delete');
