<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'car_id', 'description', 'price', 'status',
        'mechanic_id', 'claimed_at', 'completed_at',
    ];

    protected $casts = [
        'claimed_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function mechanic()
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function parts()
    {
        return $this->belongsToMany(Part::class, 'repair_parts')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
