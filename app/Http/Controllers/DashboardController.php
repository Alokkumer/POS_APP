<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function DashboardSummary(Request $request)
    { 
      $user_id = $request->header('id');
      $customer=Customer::where('user_id',$user_id)->count();
      $product=Product::where('user_id',$user_id)->count();
      $category=Category::where('user_id',$user_id)->count();
      $invoice=Invoice::where('user_id',$user_id)->count();
      $total=Invoice::where('user_id',$user_id)->sum('total');
      $discount=Invoice::where('user_id',$user_id)->sum('discount');
      $vat=Invoice::where('user_id',$user_id)->sum('vat');
      $payable=Invoice::where('user_id',$user_id)->sum('payable');
        $data =[
            'customer' => $customer,
            'product' => $product,
            'category' => $category,
            'invoice' => $invoice,
            'total' => $total,
            'discount' => $discount,
            'vat' => $vat,
            'payable' => $payable,
        ];
        return $data;
    }
}
