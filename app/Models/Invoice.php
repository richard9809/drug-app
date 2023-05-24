<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'invoice_number',
        'invoice_date',
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
