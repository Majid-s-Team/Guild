<?php

// use App\Http\Middleware\Test;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('test', [
//     'uses' => '\App\Http\Controllers\Test@index',
//     'permission'=> 'moderate_comments'
//     //'middleware' => 'PermissionWithExtraData:moderate_comments,comment' // Passing comment as additional data
// ]);

//Route::get('/upload-form', [\App\Http\Controllers\UploadFilesController::class, 'show']);
//Route::post('/uploads', [\App\Http\Controllers\UploadFilesController::class, 'upload']);

Route::get('/', function () {
    //phpinfo();
    return view('welcome');
});//->middleware(Test::class);

