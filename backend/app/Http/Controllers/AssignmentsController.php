<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

//models
use App\Models\User;
use App\Models\Assignment;


class AssignmentsController extends Controller
{
    public function assignmentCreate() {
        $active_user = Auth::user();
        $employee_id = $active_user->employee->id;

    }
}
