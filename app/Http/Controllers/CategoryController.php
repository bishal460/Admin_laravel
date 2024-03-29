<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function addCategory(Request $request){
      
        if($request->isMethod('post')){
           $data= $request->all();
           $category = new Category;
           $category->name= $data['category_name'];
           $category->parent_id =$data['parent_id'];
           $category->description = $data['description'];
           $category->url =$data['url'];
           $category->save();
           return redirect('/admin/view-category')->with('flash_message_success','Category added Successfully');

        } 
        $levels = Category::where(['parent_id'=>0])->get(); 
        return view('admin.categories.add_category')->with(compact('levels'));
    }

    public function viewCategory(){
        $categories = Category::get();

        return view ('admin.categories.view_category')->with(compact('categories'));
    }
    public function updateCategory(Request $request,$id=null){
        if($request->isMethod('post')){
            $data=$request->all();
            Category::where(['id'=>$id])->update(['name'=>$data['category_name'],'parent_id'=>$data['parent_id'],'description'=>$data['description'],'url'=>$data['url']]);
            return redirect('/admin/view-category')->with('flash_message_success','Category successfully updated');
        }

        $categories = Category::where(['id'=>$id])->first();
        $levels = Category::where(['parent_id'=>0])->get(); 
        return view('admin.categories.update_category')->with(compact('categories','levels'));
    }
    public function deleteCategory(Request $request,$id=null){
        if(!empty($id)){
            Category::where(['id'=>$id])->delete();
        
        return redirect()->back()->with('flash_message_success','Category successfully deleted');
        }
    }
}
