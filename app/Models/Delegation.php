<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Delegation extends Model
{

    use HasFactory;

    protected $table = 'delegations';
    protected $dateFormat = 'Y-m-d';

    
    protected $fillable = [
        'name',
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'delegation_id', 'delegation_id');
    }

    protected static function booted(): void
    {
        static::creating(function (Delegation $delegation) {
            if (empty($delegation->delegation_id)) {
                $latest = static::max('delegation_id');

                if (!$latest || !is_numeric($latest)) {
                    $delegation->delegation_id = '1';
                } else {
                    $delegation->delegation_id = strval(intval($latest) + 1);
                }
            }
        });
    }
}
