<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{

    protected $table = 'departments';
    protected $dateFormat = 'd-m-Y H:i:s';

    
    protected $fillable = [
        'name',
        'department_id',
    ];

    
    public function employees()
    {
        return $this->hasMany(Employee::class, 'department_id', 'department_id');
    }
}
