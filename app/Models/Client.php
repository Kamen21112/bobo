<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    // НОВО: Разрешаваме записването на тези полета
    protected $fillable = [
        'first_name', 
        'last_name', 
        'phone', 
        'email', 
        'notes'
    ];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function repairs()
    {
        return $this->hasManyThrough(Repair::class, Car::class);
    }
}