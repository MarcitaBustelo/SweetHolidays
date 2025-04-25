<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Responsable extends Model
{

    protected $table = 'responsables';
    protected $dateFormat = 'd-m-Y H:i:s';

    
    protected $fillable = [
        'name',
        'responsables_id',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'responsable_id', 'responsable_id');
    }
}
