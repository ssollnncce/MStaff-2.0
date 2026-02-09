<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\EmployeeStatus;
use Carbon\Carbon;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'position_id',
        'employment_date',
        'fired_date',
        'status_id',
        'work_format',
        'time_zone'
    ];

    /**
     * Attributes to append to JSON representation.
     */
    protected $appends = [
        'employee_name',
        'employee_department',
        'employee_position',
        'employment_date_formatted',
        'fired_date_formatted',
        'employee_status',
    ];

    /**
     * Cast dates to Carbon instances.
     */
    protected $casts = [
        'employment_date' => 'date',
        'fired_date' => 'date',
    ];

    /**
     * Get the user associated with the employee.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department associated with the employee.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Get the position associated with the employee.
     */
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    /**
     * Get the status associated with the employee.
     */
    public function status()
    {
        return $this->belongsTo(EmployeeStatus::class, 'status_id');
    }

    /**
     * Get all projects this employee created.
     */
    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    /**
     * Get all projects this employee participates in.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'employee_project', 'employee_id', 'project_id');
    }

    public function getEmployeeNameAttribute()
    {
        return $this->user ? $this->user->full_name() : null;
    }

    public function getEmployeeDepartmentAttribute()
    {
        return $this->department ? $this->department->title : null;
    }

    public function getEmployeePositionAttribute()
    {
        return $this->position ? $this->position->title : null;
    }

    public function getEmploymentDateFormattedAttribute()
    {
        return $this->employment_date ? Carbon::parse($this->employment_date)->format('m.d.y') : null;
    }

    public function getFiredDateFormattedAttribute()
    {
        return $this->fired_date ? Carbon::parse($this->fired_date)->format('m.d.y') : null;
    }

    public function getEmployeeStatusAttribute()
    {
        return $this->status ? $this->status->status_name : null;
    }
}
