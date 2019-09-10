<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get','post'],'/admin','AdminController@login');

Route::get('/logout','AdminController@logout');
Auth::routes();

Route::group(['middleware'=>['auth']],function(){
    Route::get('/admin/dashboard','AdminController@dashboard');
    Route::get('/admin/settings','AdminController@setting');
    Route::get('/admin/check-pwd','AdminController@chkPassword');
    Route::match(['get','post'],'/admin/update-pwd','AdminController@updatePassword');
    Route::match(['get','post'],'/admin/add-category','CategoryController@addCategory');
    Route::get('/admin/view-category','CategoryController@viewCategory');
    Route::match(['get','post'],'/admin/update-category/{id}','CategoryController@updateCategory');
    Route::get('/admin/delete-category/{id}','CategoryController@deleteCategory');

    // for products(admin)
    Route::match(['get','post'],'/admin/add-product','ProductController@addProduct');
    Route::get('/admin/view-product','ProductController@viewProduct');
    Route::match(['get','post'],'/admin/update-product/{id}','ProductController@updateProduct');
    Route::get('/admin/delete-product-image/{id}','ProductController@deleteImage');
    Route::get('/admin/delete-product/{id}','ProductController@deleteProduct');

    //for products  attribute
    Route::match(['get','post'],'/admin/add-attribute/{id}','ProductController@addAttribute');
    Route::get('/admin/delete-attribute/{id}','ProductController@deleteAttribute');
});

Route::get('/home', 'HomeController@index')->name('home');
