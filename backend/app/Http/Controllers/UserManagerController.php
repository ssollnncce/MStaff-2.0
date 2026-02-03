<?php

namespace App\Http\Controllers;
//Models:
use App\Models\User;
//Requests:
use http\Env\Response;
use Illuminate\Http\Request;
//Requests:
use App\Http\Requests\CreateUserRequest;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserManagerController extends Controller
{
    public function createUser(CreateUserRequest $request) {
        $data = $request->validated();
        //Create new user
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'patronymic' => $data['patronymic'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'date_of_birth' => $data['date_of_birth'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user_info' => $user
        ], 201);
    }

    public function deleteUser (int $id) {
        $user = User::find($id);
        //If the user is not found, return 404 error
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
        // Prevent self-deletion
        if (Auth::id() === $user->id) {
            return response()->json([
                'message' => 'You can not delete yourself',
            ], 403);
        }

        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully',
        ], 204);
    }

    public function editUser (CreateUserRequest $request ,int $id) {
        $user = User::find($id);
        //If the user is not found, return 404 error
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $data = $request->validated();
        //If password was update hash them
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json([
            'message' => 'User data updated successfully',
            'user_indo' => $user,
        ], 201);

    }

    public function allUsers () {
        $users = User::all();

        return response()->json([
            'message' => "Users list:",
            'users' => $users
        ]);
    }
}
