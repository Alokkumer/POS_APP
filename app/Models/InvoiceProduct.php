<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\User;

class InvoiceProduct extends Model
{
    use HasFactory;

    protected $fillable=[
        'invoice_id',
        'product_id',
        'user_id',
        'qty',
        'sale_price'
    ];

    public function invoice(){
        return $this->belongsTo(Invoice::class,'invoice_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
