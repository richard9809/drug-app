<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'drug_id',
        'quantity'
    ];

}
