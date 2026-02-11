<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'title',
        'description',
        'project_id',
        'priority',
        'status',
        'start_date',
        'due_date',
        'creator_id',
    ];

    public function project() {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function creator() {
        return $this->belongsTo(Employee::class, 'creator_id');
    }

    public function employee() {
        return $this->belongsToMany(EmployeeAssignment::class, 'employee_assignments', 'employee_id', 'assignment_id');
    }
}

