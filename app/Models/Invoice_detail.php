<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice_detail extends Model
{
    protected $fillable = [
        'product_id',
        'invoice_id',
        'price',
        'qty',
        'subtotal'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
