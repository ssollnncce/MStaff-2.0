<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//Controllers:
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserManagerController;
use App\Http\Controllers\ManageEmployeeController;

//Middlewares:
use App\Http\Middleware\CheckRole;

//Authorization routes

Route::prefix('auth')->group(function () {

    //For not authorized users
    Route::middleware(['guest'])->group(function () {
        //Login
        Route::post('/login', [AuthController::class, 'login']);
        //Password reset
        Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
        Route::post('/password/reset', [AuthController::class, 'resetPassword']);
    });

    //For authorized users
    Route::middleware(['auth:sanctum'])->group(function () {

        //Get user info
        Route::get('/user', [AuthController::class, 'userInfo']);

        //Logout
        Route::post('/logout', [AuthController::class, 'logout']);
    });

});

// Routes for admins
Route::middleware(['auth:sanctum', CheckRole::class . ':admin'])->prefix('admin')->group(function() {
    //Users management routes:
    //Functions:
    //Create, Delete, Edit, Users list.
    Route::prefix('users')->group(function () {
        Route::post('/create', [UserManagerController::class, 'createUser']);
        Route::delete("delete/{id}", [UserManagerController::class, 'deleteUser']);
        Route::put('/edit/{id}', [UserManagerController::class, 'editUser']);
        Route::get('/', [UserManagerController::class, 'allUsers']);
    });
    //Employee management routes:
    //Functions:
    //Employee list, Create, Delete, Edit.
    Route::prefix('employee')->group(function () {
        Route::get('/', [ManageEmployeeController::class, 'allEmployee']);
        Route::post('/create', [ManageEmployeeController::class, 'createEmployee']);
        Route::delete('/delete/{id}', [ManageEmployeeController::class, 'deleteEmployee']);
        Route::put('/edit', [ManageEmployeeController::class, 'editEmployee']);
    });
});
