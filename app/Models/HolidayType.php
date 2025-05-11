<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HolidayType extends Model
{

    protected $table = 'holidays_types';
    protected $dateFormat = 'Y-m-d';
    protected $fillable = [
        'type',
    ];

    public function holiday()
    {
        return $this->hasMany(Holiday::class, 'holiday_id');
    }
}
