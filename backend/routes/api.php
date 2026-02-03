<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//Controllers:
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserManagerController;
use App\Http\Controllers\ManageEmployeeController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\PositionsController;
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
        Route::get('/list', [UserManagerController::class, 'allUsers']);
    });
    //Employee management routes:
    //Functions:
    //Employee list, Create, Delete, Edit.
    Route::prefix('employee')->group(function () {
        Route::get('/list', [ManageEmployeeController::class, 'allEmployee']);
        Route::post('/create', [ManageEmployeeController::class, 'createEmployee']);
        Route::delete('/delete/{id}', [ManageEmployeeController::class, 'deleteEmployee']);
        Route::patch('/edit/{id}', [ManageEmployeeController::class, 'editEmployee']);
    });

    //Departments management routes:
    Route::prefix('departments')->group(function () {
        Route::get('/list', [DepartmentsController::class, 'allDepartments']);
        Route::post('/create', [DepartmentsController::class, 'createDepartment']);
        Route::delete('/delete/{id}', [DepartmentsController::class, 'deleteDepartment']);
        Route::put('/edit/{id}', [DepartmentsController::class, 'editDepartment']);
    });
    //Positions management routes:
    Route::prefix('positions')->group(function () {
        Route::get('/list', [PositionsController::class, 'allPositions']);
        Route::post('/create', [PositionsController::class, 'createPosition']);
        Route::delete('/delete/{id}', [PositionsController::class, 'deletePosition']);
        Route::put('/edit/{id}', [PositionsController::class, 'editPosition']);
    });
    //Create, delete and edit dictionary
    Route::prefix('dictionaries')->group(function () {
        //Employee statuses
        Route::get('/employee-statuses', [DictionaryController::class, 'listEmployeeStatuses']);
        Route::post('/employee-statuses/create', [DictionaryController::class, 'createEmployeeStatus']);
        Route::delete('/employee-statuses/delete/{id}', [DictionaryController::class, 'deleteEmployeeStatus']);
        Route::put('/employee-statuses/edit/{id}', [DictionaryController::class, 'editEmployeeStatus']);
        //Other dictionaries can be added here in the future
    });


});
