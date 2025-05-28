<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class HolidayType extends Model
{

    use HasApiTokens;
    protected $table = 'holidays_types';
    protected $dateFormat = 'Y-m-d';
    protected $fillable = [
        'type',
        'color',
    ];

    public function holiday()
    {
        return $this->hasMany(Holiday::class, 'holiday_id');
    }
}
