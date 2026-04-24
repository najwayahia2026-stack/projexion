<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AISimilarityController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// AI Similarity API
Route::prefix('ai')->group(function () {
    Route::post('/check-similarity', [AISimilarityController::class, 'checkSimilarity']);
    Route::get('/similarity-results/{project}', [AISimilarityController::class, 'getResults']);
});

// Groups API
Route::prefix('groups')->middleware('auth')->group(function () {
    Route::get('/find/{code}', function ($code) {
        $group = \App\Models\Group::where('code', $code)->first();
        return response()->json(['group' => $group ? ['id' => $group->id, 'name' => $group->name] : null]);
    });
});

