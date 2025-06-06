<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Holiday extends Model
{

    use HasApiTokens;
    protected $table = 'holidays';
    protected $dateFormat = 'Y-m-d';

    protected $fillable = [
        'holiday_id',
        'employee_id',
        'start_date',
        'end_date',
        ];
    
    public function holidayType()
    {
        return $this->belongsTo(HolidayType::class, 'holiday_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }
    

}
