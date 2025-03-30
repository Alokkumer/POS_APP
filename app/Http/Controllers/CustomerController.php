<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{   

    //customer page view
    public function CustomerPage(Request $request)
    {
        $user_id = $request->header('id');
        $customers = Customer::where('user_id', $user_id)->get();
        return Inertia::render('CustomerPage',['customers'=>$customers]);
    }

   
    //add customer  
    public function CustomerSavePage(Request $request){
        $customer_id=$request->query('id');
        $user_id = $request->header('id');
        $customer=Customer::where('id', $customer_id)->where('user_id', $user_id)->first();
        return Inertia::render('CustomerSavePage',['customer'=>$customer]);
    }


    //add customer
    public function CreateCustomer(Request $request)
    {
        $user_id = $request->header('id');

        $request->validate([
            'name' => 'required',   
            'email' => 'required|email|unique:customers,email',
            'mobile' => 'required',
        ]);
        Customer::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'user_id' => $user_id,
        ]);
        $data = ['message'=>'Customer created successfully','status'=>true,'error'=>''];
        return redirect('/CustomerPage')->with($data);
      
    } //end method

    //customer list
    public function ListCustomer(Request $request)
    {
        $user_id = $request->header('id');
        $customers = Customer::where('user_id', '=', $user_id)->get();
        return $customers;
    } //end method

    public function GetCustomerById(Request $request)
    {   

        try {
            $user_id = $request->header('id');
            $customer = Customer::where('id', $request->id)->where('user_id', $user_id)->first();
            return $customer;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage(),
            ]);
        }
     
    } //end method

    public function UpdateCustomer(Request $request)
    {
        $user_id = $request->header('id');
        $id = $request->input('id');
        Customer::where('id', $id)
            ->where('user_id', $user_id)
            ->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
            ]);
            $data = ['message'=>'Customer Update successfully','status'=>true,'error'=>''];
            return redirect('/CustomerPage')->with($data);
    }

    public function DeleteCustomer(Request $request, $id)
    {
        $user_id = $request->header('id');
        Customer::where('user_id', $user_id)->where('id', $id)->delete();
        $data=['message' => 'Customer Delete Successfully', 'status' => true, 'error' => ''];
        return redirect('/CustomerPage')->with($data);
    }
}
