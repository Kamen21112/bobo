<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'contact_person'];

    // Един доставчик може да доставя много части
    public function parts()
    {
        return $this->hasMany(Part::class);
    }
}
