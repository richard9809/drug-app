<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receipt_number',
        'receipt_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function receiptItems()
    {
        return $this->hasMany(ReceiptItem::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
