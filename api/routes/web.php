<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-migrations', function () {
    try {
        $deployToken = request()->header('X-DEPLOY-TOKEN');
        if ($deployToken !== env('DEPLOY_TOKEN')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        Artisan::call('migrate --force');
        return response()->json(['message' => 'Migrations run successfully']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to run migrations: ' . $e->getMessage()], 500);
    }
});
