<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    
    protected $table = 'companies';
    protected $dateFormat = 'd-m-Y H:i:s';

    
    protected $fillable = [
        'name',
        'company_id',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'company_id', 'company_id');
    }
}