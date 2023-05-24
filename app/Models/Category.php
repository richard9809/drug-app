<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // The attributes that are mass assignable.
    protected $fillable = [
        'name',
    ];

    // Get the drugs for the category.
    public function drugs()
    {
        return $this->hasMany(Drug::class);
    }
}
