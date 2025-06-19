<?php

use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;


Route::get('/test',[DataController::class, 'test']);

Route::get('/allteams',[DataController::class, 'getAllTeams']);

Route::get('/getPlayers',[DataController::class, 'getPlayers']);


Route::post('/saveXml',[DataController::class, 'saveXml']);


// Route::middleware('auth:sanctum')->group(function()
// {
//    
// });

