<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Holiday;
use App\Models\Department;
use App\Models\Delegation;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $dateFormat = 'Y-m-d';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'nif',
        'password',
        'role',
        'phone',
        'employee_id',
        'department_id',
        'delegation_id',
        'responsable_id',
        'start_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
            'start_date' => 'date',
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function delegation()
    {
        return $this->belongsTo(Delegation::class);
    }

    public function holidays()
    {
        return $this->hasMany(Holiday::class, 'employee_id', 'employee_id');

    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable', 'employee_id');
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->employee_id)) {
                $latest = static::max('employee_id');

                if (!$latest || !is_numeric($latest)) {
                    $user->employee_id = '10001';
                } else {
                    $user->employee_id = strval(intval($latest) + 1);
                }
            }
        });
    }
}
