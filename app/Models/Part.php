<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'part_number', 'quantity', 'price', 'supplier_id'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function repairs()
    {
        return $this->belongsToMany(Repair::class, 'repair_parts')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function partRequests()
    {
        return $this->hasMany(PartRequest::class);
    }
}
