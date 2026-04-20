<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    // Добавяме mechanic_id в списъка за записване
    // Промени този ред:
    protected $fillable = ['title', 'car_id', 'description', 'price', 'status', 'mechanic_id'];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    // Добавяме тази връзка: Ремонтът принадлежи на Механик (User)
    public function mechanic()
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }
}