<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAssignment extends Model
{
    protected $fillable = [
        'employee_id',
        'assignment_id'
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    public function assignmnet() {
        return $this->belongsTo(Assignment::class);
    }
}
