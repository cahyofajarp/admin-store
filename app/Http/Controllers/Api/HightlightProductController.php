<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HightlightProduct;
use Illuminate\Http\Request;

class HightlightProductController extends Controller
{
    public function index()
    {
        $product_highlight = HightlightProduct::with(['product' => function($q) {
            
            $q->select('id','slug','discount');

        }])
        ->whereHas('product',function($q) {
            $q->where('discount','<>',0);
        })
        ->latest()->first();

        if($product_highlight){
            return response()->json([
                'success' => true,
                'message' => 'Data product hightlight di halaman product home',
                'product_hightlight' => $product_highlight
            ],200);
        }
    }
}
