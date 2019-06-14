<?php

use Illuminate\Http\Request;



/* Open Routes start */

// Register User
Route::post('register', 'ApiController@register');

// Login User
Route::post('login', 'ApiController@login');

// Fetch Products
Route::get('products', 'ApiController@fetchProducts');

// Fetch Categories
Route::get('categories', 'ApiController@fetchCategories');

  // Fetch Carts
 Route::get('carts', 'ApiController@fetchCarts');

 // Fetch Orders
 Route::get('orders', 'ApiController@fetchOrders');

// Creating Product Category
 Route::post('category/add', 'ApiController@addCategory');

// Creating Product
Route::post('product/add', 'ApiController@addProduct');

/* Open Routes end */


/* Closed Routes requiring JWT authentication */

Route::group(['middleware' => 'jwt.auth'], function(){

// Get User by token
 Route::get('users', 'ApiController@getAuthUser');

 // Add items To Cart
 Route::post('cart/add', 'ApiController@addToCart');

 // Create an Order
 Route::post('order/add', 'ApiController@addToOrder');


});


