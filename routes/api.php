<?php

use Illuminate\Support\Facades\Route;

Route::get('/heartbeats', 'HeartbeatResourceController@index');
Route::post('/heartbeats', 'HeartbeatResourceController@storeOrUpdate');
Route::delete('/heartbeats', 'HeartbeatResourceController@delete');
Route::patch('/heartbeats', 'HeartbeatResourceController@retake');
