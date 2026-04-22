<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'mechanic_id',
        'part_id',
        'quantity',
        'price',
        'status',
        'notes',
        'status_changed_at',
    ];

    protected $casts = [
        'status_changed_at' => 'datetime',
    ];

    public function mechanic()
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'Чакаща',
            'rejected'  => 'Отказана',
            'ordered'   => 'Поръчана',
            'delivered' => 'Доставена',
            default     => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'yellow',
            'rejected'  => 'red',
            'ordered'   => 'blue',
            'delivered' => 'green',
            default     => 'gray',
        };
    }
}
