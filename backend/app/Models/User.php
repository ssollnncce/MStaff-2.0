<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'patronymic',
        'email',
        'phone_number',
        'date_of_birth',
        'photo_path',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the employee associated with the user.
     */
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Get all projects created by this user (through employee).
     */
    public function createdProjects()
    {
        return $this->hasManyThrough(
            Project::class,
            Employee::class,
            'user_id',
            'created_by'
        );
    }

    /**
     * Get all projects this user participates in (through employee).
     */
    public function projects()
    {
        return $this->hasManyThrough(
            Project::class,
            Employee::class,
            'user_id',
            'id',
            'id',
            'id'
        )->join('employee_project', 'projects.id', '=', 'employee_project.project_id')
          ->where('employee_project.employee_id', $this->employee->id ?? null);
    }

    public function full_name(): string
    {
        return trim("{$this->last_name} {$this->first_name} {$this->patronymic}");
    }
}
