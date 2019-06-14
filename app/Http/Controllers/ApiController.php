<?php
 
namespace App\Http\Controllers;
 
use App\Http\Requests\RegisterAuthRequest;
use App\Cart;
use App\Category;
use App\Order;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
 
class ApiController extends Controller
{
   
    // Function to handle registration
    public function register(RegisterAuthRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
 
        
        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }
    
    // Function to handle login
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $jwt_token = null;
 
        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }
 
        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);
    }
    
    // Function to handle logout
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
 
        try {
            JWTAuth::invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }
    
    // Function to getUSer by Token
    public function getAuthUser(Request $request)
    {
        return auth()->user();
    }
    
    // Function to Fetch All Products
    public function fetchProducts()
    {
        return Product::latest()->orderBy('created_at', 'desc')->get();
 
    }
    
    // Function to Fetch All Categories
    public function fetchCategories()
    {
        return Category::latest()->orderBy('created_at', 'desc')->get();
 
    }
    
    // Function to Fetch all Item in Cart
    public function fetchCarts()
    {
        return Cart::latest()->orderBy('created_at', 'desc')->get();
 
    }
    
    // Function to Fetch All Orders
    public function fetchOrders()
    {
        return Order::latest()->orderBy('created_at', 'desc')->get();
 
    }
    
    // Function to Add New Product
    public function addProduct(Request $request)
    {
           // validate incoming request
		    $validator = Validator::make($request->all(), [
		        'category_id' => 'required',
		   		'name' => 'required',
		   		'price' => 'required',
		        'description' => 'required'
		   ]);


		if ($validator->fails()) {
		        $errors = $validator->errors();
				return $errors->toJson();
		   } else {
		   		return Product::create(['category_id' => $request->input(['category_id']), 'name' => $request->input(['name']), 'price' => $request->input(['price']), 'image' => $request->input(['image']), 'description' => $request->input(['description']) ]);
		   }

    }
    
    // Function to Add a New Category
    public function addCategory(Request $request)
    {
	        // validate incoming request
	        $validator = Validator::make($request->all(), [
	            'name' => 'required'
	       ]);

	    if ($validator->fails()) {
	            $errors = $validator->errors();
				return $errors->toJson();
	       } else {
	       		return Category::create([ 'name' => $request->input(['name']) ]);
	       }

    }
    
    // Function to Add item to cart
    public function addToCart(Request $request)
    {
	         // validate incoming request
	        $validator = Validator::make($request->all(), [
	            'product_id' => 'required',
		   		'quantity' => 'required',
		        'total_price' => 'required'
	       ]);

	       if ($validator->fails()) {
	            $errors = $validator->errors();
				return $errors->toJson();
	       } else {
	       	     $user_id = auth()->user()->id;
	       		 return Cart::create([ 'user_id' => $user_id, 'product_id' => $request->input(['product_id']), 'quantity' => $request->input(['quantity']), 'total_price' => $request->input(['total_price']) ]);
	       }

    }

    // Function to move item from cart for order processing
    public function addToOrder(Request $request)
    {
	        // validate incoming request
	        $validator = Validator::make($request->all(), [
	        	'cart_id' => 'required',
	            'product_id' => 'required',
		   		'quantity' => 'required',
		        'total_price' => 'required'
	       ]);

	       if ($validator->fails()) {
	            $errors = $validator->errors();
				return $errors->toJson();
	       } else {

	       		$cart_id = $request->input(['cart_id']);
			   //Delete item from cart
			   Cart::destroy($cart_id);
			   // add item to order
			    $user_id = auth()->user()->id;
			   return Order::create([ 'user_id' => $user_id, 'product_id' => $request->input(['product_id']), 'quantity' => $request->input(['quantity']), 'total_price' => $request->input(['total_price']) ]);
	       }

    }


  
}
