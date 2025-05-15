<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Arrival extends Model
{
    use HasApiTokens;
    protected $table = 'arrivals';
    protected $dateFormat = 'Y-m-d';

    protected $fillable = [
        'employee_id',
        'date',
        'arrival_time',
    ];
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id', 'employee_id');
    }
}
