<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Exception;

class ProductController extends Controller
{  

    //create product
    public function CreateProduct(Request $request)
    {
      
            $user_id = $request->header('id');
            $request->validate([
                'name' => 'required',
                'category_id' => 'required',
                'price' => 'required',
                'unit' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $data = [
                'name' => $request->name,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'unit' => $request->unit,
                'user_id' => $user_id,
            ];
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $filePath = 'uploads/' . $fileName;
                $image->move(public_path('uploads'), $fileName);
                $data['image'] = $filePath;
            }
            Product::create($data);
            $data = ['message'=>'Product created successfully','status'=>true,'error'=>''];
            return redirect('/ProductPage')->with($data);
        }
    //end method

    //product add page view
    public function ProductSavePage(Request $request)
    {
        $user_id = $request->header('id');
        $product_id=$request->query('id');
        $products=Product::where('id', $product_id)->where('user_id', $user_id)->first();
        $categories = Category::where('user_id', $user_id)->get();
        return Inertia::render('ProductSavePage', ['products' => $products,'categories'=>$categories]);
    }


    

    //show all product list
    public function ProductPage(Request $request)
    {
        $user_id = $request->header('id');
        $products = Product::where('user_id', $user_id)->with('category')->latest()->get();
        return Inertia::render('ProductPage', ['products' => $products]);
    }

    

    //List product
    public function ListProduct(Request $request)
    {
        
            $user_id = $request->header('id');
            $products = Product::where('user_id', $user_id)->get();
            return $products;
           
    }

    //product byy id
    public function ProductById(Request $request){
        $user_id = $request->header('id');
        $category = Product::where('id', $request->id)->where('user_id', $user_id)->first();
        return $category;
    }

    //update product
    public function UpdateProduct(Request $request)
    {
       
            $user_id = $request->header('id');
            $request->validate([
                'name' => 'required',
                'category_id' => 'required',
                'price' => 'required',
                'unit' => 'required',
            ]);

            $product = Product::where('id', $request->id)->findOrFail($request->id);
            $product->name = $request->name;
            $product->category_id = $request->category_id;
            $product->price = $request->price;
            $product->unit = $request->unit;

            if ($request->hasFile('image')) {
                if ($product->image && file_exists(public_path($product->image))) {
                    unlink(public_path($product->image));
                }
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);

                $image = $request->file('image');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $filePath = 'uploads/' . $fileName;
                $image->move(public_path('uploads'), $fileName);
                $product->image = $filePath;
            }
            $product->save();
            $data = ['message'=>'Product updated successfully','status'=>true,'error'=>''];
            return redirect('/ProductPage')->with($data);
        
    } //end method

    //delete product
    public function DeleteProduct(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            $product->delete();
            $data = ['message'=>'Product Deleted successfully','status'=>true,'error'=>''];
            return redirect()->back()->with($data);
        } catch (Exception $e) {
            $data = ['message'=> 'Something went wrong','status'=>false,'error'=>$e->getMessage()];
            return redirect()->back()->with($data);
        }
    }//end method
}
