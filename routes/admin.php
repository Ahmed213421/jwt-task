<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;

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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/dashboard')->middleware('admin')->name('admin.')->group(function(){
    Route::get('dashboard',function(){
        return view('dashboard.index');
    })->name('dashboard');
    Route::resource('profile/settings', Admin\ProfileSettingController::class)->names('profile');
});

Route::prefix('/dashboard')->middleware('admin')->name('admin.')->group(function(){
    Route::resource('permissions', Admin\PermissionController::class);

    Route::get('roles/{roleId}/delete', [Admin\RoleController::class, 'destroy']);
    Route::get('roles/{roleId}/give-permissions', [Admin\RoleController::class, 'addPermissionToRole']);
    Route::put('roles/{roleId}/give-permissions', [Admin\RoleController::class, 'givePermissionToRole']);
    Route::resource('roles', Admin\RoleController::class);

    Route::get('users/{userId}/delete', [Admin\AdminController::class, 'destroy']);
    Route::resource('users', Admin\AdminController::class);
    Route::resource('posts', PostController::class);
});

Route::prefix('/dashboard')->middleware('admin')->name('admin.')->group(function(){
    Route::get('forgot-password', [Admin\Auth\AdminPasswordResetLinkController::class, 'create'])
        ->name('password.request')->withoutMiddleware('admin');

    Route::post('forgot-password', [Admin\Auth\AdminPasswordResetLinkController::class, 'store'])
        ->name('password.email')->withoutMiddleware('admin');

    Route::get('reset-password/{token}', [Admin\Auth\AdminNewPasswordController::class, 'create'])
        ->name('password.reset')->withoutMiddleware('admin');

    Route::post('reset-password', [Admin\Auth\AdminNewPasswordController::class, 'store'])
        ->name('password.store')->withoutMiddleware('admin');
});

Route::middleware('guest')->group(function () {

        Route::get('admin/login', [App\Http\Controllers\Admin\Auth\AuthenticatedSessionController::class, 'create'])
            ->name('admin.login');

        Route::post('admin/login', [App\Http\Controllers\Admin\Auth\AuthenticatedSessionController::class, 'store'])->name('admin.loginstore');
        Route::get('admin/register', [App\Http\Controllers\Admin\Auth\RegisteredUserController::class, 'create'])->name('admin.loginstore.create');
        Route::post('admin/register', [App\Http\Controllers\Admin\Auth\RegisteredUserController::class, 'store'])->name('admin.loginstore.store');
    });

require __DIR__.'/auth.php';
