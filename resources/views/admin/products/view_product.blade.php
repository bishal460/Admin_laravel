@extends('layouts.adminLayout.admin_design')
@section('content')


<div id="content">
  <div id="content-header">
  <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Products</a> <a href="#" class="current">View Product</a> </div>
   <h1>Product</h1>
  
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>View Product</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Proudct ID</th>
                  <th>Category ID</th>
                  <th>Category Name</th>
                  <th>Product Name</th>
                  <th>Product Code</th>
                  <th>Product Color</th>
                  <!-- <th>Description</th> -->
                  <th>Price</th>
                  <th>Image </th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  @foreach($products as $product)
                <tr class="gradeX">
                  <td>{{$product->id}}</td>
                  <td>{{$product->category_id}}</td>
                  <td>{{$product->category_name}}</td>
                  <td>{{$product->product_name}}</td>
                  <td>{{$product->product_code}}</td>
                  <td>{{$product->product_color}}</td>
                  <!-- <td>{{$product->description}}</td> -->
                  <td>{{$product->price}}</td>
                  <td>
                  
                  @if(!empty($product->image))
                  <img src="{{asset('/images/backend_images/products/small/'.$product->image)}}" style="width:50px;" >
                  @endif
                  </td>
                  <td> <a href="#myModal{{$product->id}}" data-toggle="modal" class="btn btn-success">View</a>|
                   <a href="{{url('/admin/update-product/'.$product->id)}}" class="btn btn-primary">Edit</a> |
                   <a href="{{url('/admin/add-attribute/'.$product->id)}}" class="btn btn-primary">Add</a> |
                    <a href="{{url('/admin/delete-product/'.$product->id)}}" class="btn btn-danger">Delete</a></td>
               </tr>
               

               
            <div id="myModal{{$product->id}}" class="modal hide">
              <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button">×</button>
                <h3>{{$product->category_name}}</h3>
              </div>
              <div class="modal-body">
               <p>Name: {{$product->product_name}}  </p>
               <p>Code: {{$product->product_code}}</p>
               <p>Color: {{$product->product_color}}</p>
               <p>Description: {{$product->description}}</p>
               <p>Price: {{$product->price}}</p>
              </div>
            </div>
            
          
                @endforeach
               
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


          
        
@endsection