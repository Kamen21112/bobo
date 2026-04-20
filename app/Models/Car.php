<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    // НОВО: Добавяме client_id към разрешените полета
    protected $fillable = [
        'client_id', 
        'make',
        'model',
        'plate_number',
        'vin',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }
}