<?php

use App\Http\Controllers\Admin\AdminKycController;
use App\Http\Controllers\Admin\ApisKeysController;
use App\Http\Controllers\Admin\AadminKycController;
use App\Http\Controllers\Admin\BannersController;
use App\Http\Controllers\Admin\BlogsController;
use App\Http\Controllers\Admin\BroadCastController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SalesAgentsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\WebUsersController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignOutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KycController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesAgentController;
use App\Http\Controllers\Auth\SignUpController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::get('countries', [HomeController::class,'getCountries']);


Broadcast::routes(['middleware' => ['auth:api']]);

//Route::middleware(['auth:api'])->group(function () {

    Route::prefix('admin')->group(function () {
        Route::resource('users', UsersController::class);
        Route::resource('banners', BannersController::class);
        Route::resource('blogs', BlogsController::class);
        Route::resource('roles', RolesController::class);
        Route::resource('permissions', PermissionsController::class);
        Route::resource('apiskeys', ApisKeysController::class);
        Route::resource('salesAgents', SalesAgentsController::class);
        Route::resource('webusers', WebUsersController::class);
        Route::get('kyc', [AdminKycController::class, 'index']);
        Route::get('kyc/stats', [AdminKycController::class, 'stats']);
        Route::get('kyc/{kyc}', [AdminKycController::class, 'show']);
        Route::post('kyc/{kyc}/approve', [AdminKycController::class, 'approve']);
        Route::post('kyc/{kyc}/decline', [AdminKycController::class, 'decline']);
        Route::get('salesAgents/{id}/analytics', [SalesAgentsController::class, 'analytics']);
    });
//});

Route::get('sales-agent/{identifier}', [SalesAgentController::class, 'show']);
Route::apiResource('kyc', KycController::class);

Route::prefix('auth')->group(function (){

    Route::post('signin', [SignInController::class, 'SignIn']);
    Route::post('user-signin', [SignInController::class, 'UserSignIn']);
    Route::post('signup', [SignUpController::class, 'register']);
    Route::post('signupComplete', [SignUpController::class, 'signupComplete']);

    Route::middleware(['auth:api'])->group(function () {
        Route::get('me', [MeController::class, 'index']);
        Route::post('signout', [SignOutController::class, 'SignOut']);
    });
});

Route::get('ips', function (){
    return response()->json([
        'public_ip' => request()->header('X-Forwarded-For'),
        'laravel_ip' => request()->ip(),
        'client_ip' => request()->getClientIp(),
    ]);
});



/*Route::middleware(['auth:api'])->group(function () {
    Route::resource('feedbacks', FeedbackController::class);
    Route::get('user-feedbacks', [FeedbackController::class, 'getUserFeedbacks']);
    Route::Resource('admin/comments', AdminCommentController::class);
    Route::put('admin/comments/{comment}/moderate', [AdminCommentController::class, 'moderate'])
        ->middleware('can:moderate-comment');
    Route::Resource('admin/feedbacks', AdminFeedBackController::class);
    Route::resource('votes', VoteController::class);


    Route::prefix('feedbacks/{feedback}')->group(function () {
        Route::apiResource('comments', CommentController::class);
    });

    Route::resource('notifications', NotificationController::class);

});

Route::resource('roles', RolesController::class);
Route::post('/roles/{role}/permissions', [RolePermissionController::class, 'assignPermissionsToRole']);
Route::post('/roles/assign-roles-to-user', [RolesController::class, 'assignRole']);

Route::resource('permissions', \App\Http\Controllers\Roles\PermissionController::class);


Route::post('upload-csv', [UploadFilesController::class, 'uploadCsv']);


Route::get('admin/fire-broadcast', [BroadCastController::class, 'index']);*/









