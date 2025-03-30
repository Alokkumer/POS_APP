<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //category page view
    public function CategoryPage(Request $request)
    {
        $user_id = $request->header('id');
        $categories = Category::where('user_id', $user_id)->get();
        return Inertia::render('CategoryPage',['categories'=>$categories]);
    }
    

    //add category page view
    Public function CategorySavePage(Request $request){
         $category_id=$request->query('id');
         $user_id = $request->header('id');
         $category=Category::where('id', $category_id)->where('user_id', $user_id)->first();
         return Inertia::render('CategorySavePage',['category'=>$category]);
    }

    //add category
    public function CreateCategory(Request $request)
    {
        $user_id = $request->header('id');
        Category::create([
            'name' => $request->input('name'),
            'user_id' => $user_id,
        ]);
        $data = ['message'=>'Category created successfully','status'=>true,'error'=>''];
        return redirect('/CategoryPage')->with($data);
    } //end method

    //category list
    public function ListCategory(Request $request)
    {
        $user_id = $request->header('id');
        $category = Category::where('user_id', '=', $user_id)->get();
        return $category;
    } //end method

    public function GetCategoryById(Request $request)
    {
        $user_id = $request->header('id');
        $category = Category::where('id', $request->user_id)->where('user_id', $user_id)->first();
        return $category;
    } //end method

    public function UpdateCategory(Request $request)
    {
        $user_id = $request->header('id');
        $id = $request->input('id');
        Category::where('id', $id)
            ->where('user_id', $user_id)
            ->update(['name' => $request->input('name')]);

            $data = ['message'=>'Category Update successfully','status'=>true,'error'=>''];
            return redirect('/CategoryPage')->with($data);
    }

    public function DeleteCategory(Request $request, $id)
    {
        $user_id = $request->header('id');
        Category::where('id', $id)->where('user_id', $user_id)->delete();
         $data=['message' => 'Category Delete Successfully', 'status' => true, 'error' => ''];
        return redirect('/CategoryPage')->with($data);
    }
}
