<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Festive extends Model
{
    protected $table = 'festives';

    protected $fillable = [
        'name',
        'date',
        'delegation_id',
        'national',
    ];

    public function delegation()
    {
        return $this->belongsTo(Delegation::class, 'delegation_id', 'delegation_id');
    }
}