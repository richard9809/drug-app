<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'number_of_dispensaries',
    ];

    public function dispensaries()
    {
        return $this->hasMany(Dispensary::class);
    }
}
