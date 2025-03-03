<?php


use App\Http\Controllers\NotifyController;
use App\Http\Middleware\ApiRequestLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::middleware([ApiRequestLog::class])->group(function () {
    Route::get('/test', function () {
        return response()->json(['message' => 'SCB Notifier API is up and running']);
    });

    Route::post('/notify/cashbaba', [NotifyController::class,'notify'])->name('');


    Route::get('/users', function () {
        return response()->json(['users' => []]);
    });
});
