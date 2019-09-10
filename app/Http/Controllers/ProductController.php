<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;
use Auth;
use Session;
use App\Category;
use App\Product;
use App\ProductsAttribute;

class ProductController extends Controller
{
    public function addProduct(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();

            $product = new Product;
            if(empty($data['category_id'])){return redirect()->back()->with('flash_message_error','Under Category is missing');}
            $product->category_id= $data['category_id'];
            $product->product_name = $data['product_name'];
            $product->product_code = $data['product_code'];
            $product->product_color = $data['product_color'];
            if(!empty($data['product_description'])){
                $product->description = $data['product_description'];
            }else{
                $product->description ='';
            }
         
            $product->price = $data['price'];
            

            // upload image

            if($request->hasFile('image')){
                 $image_tmp = Input::file('image');
                if($image_tmp->isValid()){
                    
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;

                    // Resize Image
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);

                    // Store image in products table
                    $product->image = $filename;
                    
                }
                

            }


            $product->save();
            return redirect()->back()->with('flash_message_success','Product Successfully added');
        }

        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option value='' selected disabled> Select</option>";
        foreach($categories as $cat)
        {
            $categories_dropdown .= "<option value='".$cat->id."'>".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach($sub_categories as $sub_cat){
                $categories_dropdown .="<option value = '".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->name."</option>";

            }
        }

        return view('admin.products.add_products')->with(compact('categories_dropdown'));

    }

    public function viewProduct(){
        $products = Product::get();
        $products = json_decode(json_encode($products));
        
         foreach($products as $key => $val)
            {
                 $category_name=Category::where(['id'=>$val->category_id])->first();
                 $products[$key]->category_name = $category_name['name'];
             }
            // echo "<pre>";print_r($products);die; 

        return view('admin.products.view_product')->with(compact('products'));
    }

    public function updateProduct(Request $request, $id=null){

        if($request->isMethod('post')){
            $data = $request->all();

            if($request->hasFile('image')){
                $image_tmp = Input::file('image');
               if($image_tmp->isValid()){
                   
                   $extension = $image_tmp->getClientOriginalExtension();
                   $filename = rand(111,99999).'.'.$extension;
                   $large_image_path = 'images/backend_images/products/large/'.$filename;
                   $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                   $small_image_path = 'images/backend_images/products/small/'.$filename;

                   // Resize Image
                   Image::make($image_tmp)->save($large_image_path);
                   Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                   Image::make($image_tmp)->resize(300,300)->save($small_image_path);

                  
                   
               }
               else{
                   $filename=$data['current_image'];
               }
               

           }
           if(empty($data['product_description'])){
               $data['product_description'] = '';
           }

            // echo "<pre>"; print_r($data);die;
            Product::where(['id'=>$id])->update(['category_id'=>$data['category_id'],'product_name'=>$data['product_name'],
            'product_code'=>$data['product_code'],'product_color'=>$data['product_color'],'description'=>$data['product_description'],
            'price'=>$data['price'],'image'=>$filename
            ]);
            return redirect()->back()->with('flash_message_success','Product has been successfully updated');
                }


        $product = Product::where(['id'=>$id])->first();

        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option value='' selected disabled> Select</option>";
        foreach($categories as $cat)
        {
            if($cat->id==$product->category_id){
                $selected ="selected";
            }
            else{
                $selected ="";
            }
            $categories_dropdown .= "<option value='".$cat->id."'  ".$selected." >".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach($sub_categories as $sub_cat){
                if($sub_cat->id==$product->category_id){
                    $selected ="selected";
                }
                else{
                    $selected ="";
                }

                $categories_dropdown .="<option value = '".$sub_cat->id."' ".$selected.">&nbsp;--&nbsp;".$sub_cat->name."</option>";

            }
        }

        return view('admin.products.update_products')->with(compact('product','categories_dropdown'));
    }
    
    public function deleteImage($id=null){
        Product::where(['id'=>$id])->update(['image'=>'']);
        return redirect()->back()->with('flash_message_success','Product image has been successfully deleted');
    }
    public function deleteProduct($id=null){
        Product::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Product has been successfullt deleted');
    }

    public function addAttribute(Request $request, $id=null){
        $product = Product::with('attributes')->where(['id'=>$id])->first();
        // $product = json_decode(json_encode($product));
        // echo "<pre>"; print_r($product);die;

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            foreach($data['sku'] as $key => $val){
                if(!empty($val)){
                    $attributes = new ProductsAttribute;
                    $attributes->product_id = $id;
                    $attributes->sku = $val;
                    $attributes->size = $data['size'][$key];
                    $attributes->price = $data['price'][$key];
                    $attributes->stock = $data['stock'][$key];
                    $attributes->save();

                   
                }


            }
            return redirect('admin/add-attribute/'.$id)->with('flashe_message_success','Attributes added succesfully');
        }
        return view('admin.products.add_attributes')->with(compact('product'));

    }
    public function deleteAttribute($id=null){
        ProductsAttribute::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Attriutes has been succesfully delted');

    }
}
