<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Wishlist;
use App\Models\Product;

class WishlishController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum','verified-api']);
    }

    public function index()
    {
        $wishlist = Wishlist::with(['product.productgallery_thumbnails.product_gallery' => function($q) {
            return $q->select('id','image');
        },
        'product.flashsale_products.flashsale' => function($q){
                
            $q->select('id','title','status')->where('status','ongoing');
            
        }])->where('customer_id',auth()->guard('api')->user()->id)->get();

        $wishlistCount = Wishlist::where('customer_id',auth()->guard('api')->user()->id)
        ->count();
        
        return response()->json([
            'success' => true,
            'message' => 'Semua data wislisht'.auth()->guard('api')->user()->name,
            'wishlistCount' => $wishlistCount,
            'wishlists' => $wishlist
        ]);
    }

    public function store(Request $request)
    {   
        $code = null;
        $message = null;
        $wishlistCount = null;
        $success = false;

        if($request->product_id){
            
            $checkProductID = Wishlist::where('product_id',$request->product_id)
                                ->where('customer_id',auth()->guard('api')->user()->id)
                                ->first();
            if($checkProductID){
                $code = 422;
                $message = 'Oops this product is already exists in wishlist :)';
            }
            else{
                Wishlist::create([
                    'product_id' => $request->product_id,
                    'customer_id' => auth()->guard('api')->user()->id
                ]);
                
                $message = 'Sukses menambahkan data wishlist';
                $code = 200;
                $success = true;
            }

            $wishlistCount = Wishlist::where('customer_id',auth()->guard('api')->user()->id)
            ->count();
        }

        return response()->json([
            'success' => $success,
            'wishlistCount' => $wishlistCount,
            'message' => $message,
        ],$code);
    }

    public function searchWishlist(Request $request)
    {       
            
            $message = $request->keyword ? 'Data semua product wishlist keyword : '.$request->keyword : 'Semua data product wishlist';
            $code = 200;
            $success = true;

            $wishlist = Wishlist::with(['product.productgallery_thumbnails.product_gallery' => function($q) {
                return $q->select('id','image');
            },
            'product.flashsale_products.flashsale' => function($q){
                    
                $q->select('id','title','status')->where('status','ongoing');
                
            }])
            ->whereHas('product',function($q) use ($request){
                
                return $request->keyword ? $q->where('title','like', '%' . $request->keyword. '%') : '';
                
            })
            ->where('customer_id',auth()->guard('api')->user()->id)->get();
            
            return response()->json([
                'success' => $success,
                'message' => $message,
                'wishlists' => $wishlist
            ],$code);

       
    }

    public function delete(Request $request)
    {
        if($request->product_id){
            
            $delete = Wishlist::whereIn('product_id',$request->product_id)->delete();
            
           if($delete) {
                $wishlist = Wishlist::with(['product.productgallery_thumbnails.product_gallery' => function($q) {
                    return $q->select('id','image');
                },
                'product.flashsale_products.flashsale' => function($q){
                        
                    $q->select('id','title','status')->where('status','ongoing');
                    
                }])->where('customer_id',auth()->guard('api')->user()->id)->get();
    
            $wishlistCount = Wishlist::where('customer_id',auth()->guard('api')->user()->id)
            ->count();

            return response()->json([
                'success' => true,
                'message' => 'Semua data wislisht'.auth()->guard('api')->user()->name,
                'wishlistCount' => $wishlistCount,
                'wishlists' => $wishlist
            ]);
           }
        }
    }
}
