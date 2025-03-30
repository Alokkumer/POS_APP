<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Exception;
use Inertia\Inertia;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\InvoiceProduct;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{    



    //invoice List page show
    public function InvoiceListPage(Request $request){
        $user_id = request()->header('id');
        $list = Invoice::where('user_id', $user_id)
            ->with('customer','invoiceProduct.product')->get();
        return Inertia::render('InvoiceListPage', ['list' => $list]);
    }
    //create invoice
    public function CreateInvoice(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            $data = [
                'user_id' => $user_id,
                'customer_id' => $request->customer_id,
                'total' => $request->total,
                'discount' => $request->discount,
                'vat' => $request->vat,
                'payable' => $request->payable,
            ];
            $invoice = Invoice::create($data);
            //Product ace kina check korar jonno
            $products = $request->input('products');
            foreach ($products as $product) {
                $existUnit = Product::where('id', $product['id'])->first();
                if (!$existUnit) {
                    return response()->json([
                        'status' => 'fail',
                        'message' => "Product {$product['id']} not found",
                    ]);
                }
                if ($existUnit->unit < $product['unit']) {
                    return response()->json([
                        'status' => 'fail',
                        'message' => "Only {$existUnit->unit} unit available in stock product id {$product['id']}",
                    ]);
                }

                //create invoice product
                InvoiceProduct::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product['id'],
                    'user_id' => $user_id,
                    'unit' => $product['unit'],
                    'qty' => $product['unit'],
                    'sale_price' => $product['price'],
                ]);
                Product::where('id', $product['id'])->update([
                    'unit' => $existUnit->unit - $product['unit'],
                ]);
            } //end foreach

            DB::commit();
            $data = ['message'=>'Invoice created successfully','status'=>true,'error'=>''];
            return redirect('/InvoiceListPage')->with($data);
        } catch (Exception $e) {
            DB::rollBack();
            $data = ['message'=>'Something went wrong','status'=>false,'error'=>$e->getMessage()];
            return redirect()->back()->with($data);
        }
    } //end method

    //Invoice List
    public function InvoiceList()
    {
        $user_id = request()->header('id');
        $invoice = Invoice::with('customer')->where('user_id', $user_id)->get();
        return $invoice;
    } //end method

    //invoice details
    public function InvoiceDetails(Request $request)
    {
        $user_id = request()->header('id');
        $customerDetails = Customer::where('user_id', $user_id)->where('id', $request->customer_id)->first();
        $invoiceDetails = Invoice::where('user_id', $user_id)->where('id', $request->invoice_id)->first();
        $invoiceProduct = InvoiceProduct::where('invoice_id', $request->invoice_id)->where('user_id', $user_id)->with('product')->get();

        return [
            'customer' => $customerDetails,
            'invoice' => $invoiceDetails,
            'invoiceProduct' => $invoiceProduct,
        ];
    } //end method

    //invoice delete
    public function InvoiceDelete(Request $request, $id)
    {    DB::beginTransaction();
        try {
            $user_id = request()->header('id');
            InvoiceProduct::where('invoice_id', $id)
                ->where('user_id', $user_id)
                ->delete();

            Invoice::where('id', $id)
                ->where('user_id', $user_id)
                ->delete();

            DB::commit();
            $data = ['message'=>'Invoice deleted successfully','status'=>true,'error'=>''];
            return redirect()->back()->with($data);
        }catch(Exception $e){
            DB::rollBack();
          
            $data = ['message'=>'Something went wrong','status'=>false,'error'=>$e->getMessage()];
            return redirect()->back()->with($data);
    } //end method
}
}
