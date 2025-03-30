<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Product;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SellController extends Controller
{
    public function SalePage(Request $request){
        $user_id = $request->header('id');
        $customers = Customer::where('user_id', $user_id)->get();
        $products = Product::where('user_id', $user_id)->get();
        return Inertia::render('SalePage', ['customers' => $customers,'products'=>$products]);
    }
}
