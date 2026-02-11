<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'description',
        'start_date',
        'due_date',
        'priority',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
    ];

    public function creator() {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    /**
     * Get all employees participating in this project.
     */
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_projects', 'project_id', 'employee_id');
    }

    /**
     * Get all users participating in this project (through employees).
     */
    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            Employee::class,
            'id',
            'id',
            'id',
            'user_id'
        )->distinct();
    }

    public function assignments() {
        return $this->hasMany(Assignment::class);
    }
}
