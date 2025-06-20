<?php

use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;


Route::get('/test',[DataController::class, 'test']);

Route::get('/allteams',[DataController::class, 'getAllTeams']);

Route::get('/getPlayers',[DataController::class, 'getPlayers']);

Route::get('/getMaxMinDob',[DataController::class, 'getMaxMinDobOfplayers']);

Route::post('/saveXml',[DataController::class, 'saveXml']);

Route::post('/getPlayerDataByFilter',[DataController::class, 'getPlayerDataForFilter']);




// Route::middleware('auth:sanctum')->group(function()
// {
//    
// });

