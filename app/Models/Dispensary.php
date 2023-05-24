<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispensary extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
