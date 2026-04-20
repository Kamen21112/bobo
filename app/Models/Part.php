<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'part_number', 'quantity', 'price', 'supplier_id'];

    // Една част идва от един доставчик
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
