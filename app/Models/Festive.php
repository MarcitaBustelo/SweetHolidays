<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
class Festive extends Model
{
    protected $table = 'festives';
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'date',
        'delegation_id',
        'national',
    ];

    public function delegation()
    {
        return $this->belongsTo(Delegation::class, 'delegation_id');
    }

}