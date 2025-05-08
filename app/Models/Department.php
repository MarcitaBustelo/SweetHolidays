<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';
    protected $dateFormat = 'Y-m-d';

    
    protected $fillable = [
        'name',
    ];

    
    public function user()
    {
        return $this->hasMany(Employee::class, 'department_id', 'department_id');
    }

    protected static function booted(): void
    {
        static::creating(function (Department $department) {
            if (empty($department->department_id)) {
                $latest = static::max('department_id');

                if (!$latest || !is_numeric($latest)) {
                    $department->department_id = '1';
                } else {
                    $department->department_id = strval(intval($latest) + 1);
                }
            }
        });
    }
}
