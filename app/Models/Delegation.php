<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    protected $table = 'delegations';
    protected $dateFormat = 'd-m-Y H:i:s';

    
    protected $fillable = [
        'name',
        'delegation_id',
    ];

    public function employees()
    {
        return $this->hasMany(User::class, 'delegation_id', 'delegation_id');
    }
}
