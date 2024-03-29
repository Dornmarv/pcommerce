<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [ 'user_id', 'product_id', 'quantity', 'total_price', 'cart_id'];
}
