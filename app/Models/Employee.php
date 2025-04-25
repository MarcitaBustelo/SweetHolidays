<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use Notifiable, HasRoles;

    protected $table = 'employees';
    protected $dateFormat = 'd-m-Y H:i:s';

    protected $fillable = [
        'company_id',
        'delegation_id',
        'full_name',
        'NIF',
        'employee_id',
        'professional_email',
        'department_id',
        'phone',
        'start_date',
        'responsable_id',
        'days',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function delegation()
    {
        return $this->belongsTo(Delegation::class, 'delegation_id', 'delegation_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function responsable()
    {
        return $this->belongsTo(Responsable::class, 'responsable_id', 'responsable_id');
    }

    public function holidays()
    {
        return $this->hasMany(Holiday::class, 'employee_id', 'id');
    }
}
