<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_id',
        'drug_id',
        'quantity',
    ];

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }
}
