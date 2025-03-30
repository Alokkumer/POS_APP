<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\User;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable=[
        'total',
        'discount',
        'vat',
        'payable',
        'user_id',
        'customer_id'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }

    public function invoiceProduct(){
        return $this->hasMany(InvoiceProduct::class);
    }
}
