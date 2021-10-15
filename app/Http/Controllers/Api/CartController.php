<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Stock;
use App\Models\Flashsale;
use App\Models\ProductGalleries;
use App\Models\productgallery_thumbnail;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * middleware
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum','verified-api']);
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */

    public function index()
    {   
        $isFlashsale = null;

        $flashsale = Flashsale::where('status','ongoing')->latest()->first();

        if($flashsale){
            $isFlashsale = $flashsale;
        }
       
        $carts = Cart::with(['product.flashsale_products.flashsale' => function($q){
                
            $q->select('id','title','status')->where('status','ongoing');
            
        },'color','size','product_gallery'])
            ->where('customer_id',auth()->guard('api')->user()->id)
            ->latest()
            ->orderBy('color_id')
           
            ->orderBy('product_id')
            ->get();

            return response()->json([
                'success' => true,
                'message' => 'List Data Cart',
                'cart' => $carts,
                'flashsale' => $isFlashsale
            ]);

    }
    /**
     * 
     * 
     */

     public function updateCart(Request $request)
     {
        $item = Cart::where('product_id',$request->product_id)
                ->where('color_id',$request->color_id)
                ->where('customer_id',$request->customer_id);
        
        if($request->type == 'inc'){
            $item->increment('qty');

            $item->first()->update([
                'price' => $request->price + $item->first()->price,
                'weight' => $request->weight * $item->first()->qty,
                
            ]);
        }else if($request->type == 'dec'){
            
            if($item->first()->qty == 1){
                $deleteCart = $item->first();
                $deleteCart->delete();

                return;
            }

            $item->decrement('qty');
            
            $item->first()->update([
                'price' => $item->first()->price - $request->price,
                'weight' => $item->first()->weight - $request->weight,
            ]);
        }
        
        $carts = Cart::with(['product','product_gallery'])
            ->where('customer_id',auth()->guard('api')->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Product Qty has been added in your cart',
            'carts' => $carts,
        ],200);

    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {   
        
        $item = Cart::where('product_id',$request->product_id)
                ->where('customer_id',$request->customer_id)
                ->where('color_id',$request->color_id)
                ->where('size_id',$request->size_id);
        
        $updateQty = $request->qty;
            
        $checkStock = Stock::where('color_id',$request->color_id)
                        ->where('size_id',$request->size_id)
                        ->where('product_id',$request->product_id)
                        ->first();
        
        if($checkStock){
            if($checkStock->stock != 0){
                if($item->count() > 0 ){
                    // jika ada maka akan di tambahkan saja qty nya sama weight
        
                    // $item->increment('qty');
                    
                    $updateQty += $item->first()->qty;
                    
                    $updatePrice = $request->price * $updateQty;
        
                    $updateWeight = $request->weight * $updateQty;
        
                    $item->first()->update([
                        'price' => $updatePrice,
                        'weight' => $updateWeight,
                        'qty' => $updateQty,
                        
                    ]);
                }else{
                    $item = Cart::create([
                        'customer_id' => $request->customer_id,
                        'product_id' => $request->product_id,
                        'qty' => $request->qty,
                        'price' => $request->price * $updateQty,
                        'weight' => $request->weight * $updateQty,
                        'color_id' => $request->color_id,
                        'size_id' => $request->size_id,
                        'product_galleries_id' => $request->product_galleries_id,
                    ]);
                }
    
                $carts = Cart::where('customer_id',$request->customer_id)->get()->count();
           
                return response()->json([
                    'success' => true,
                    'message' => 'Product has been added in your cart',
                    'price' => $item->first()->price,
                    'weight' => $item->first()->weight,
                    'cartCount' => $carts
                ],200);
            }
            else{
                $carts = Cart::where('customer_id',$request->customer_id)->get()->count();

                return response()->json([
                    'success' => false,
                    'message' => 'Product ini tidak tersedia silahkan check terlebih dahulu stock product.',
                    'cartCount' => $carts
                ]);
            }
        }
        else{
            
            $carts = Cart::where('customer_id',$request->customer_id)->get()->count();

            return response()->json([
                'success' => false,
                'message' => 'Product ini tidak tersedia.',
                'cartCount' => $carts
            ]);
        }
       
        

    }

    /**
     * Undocumented function
     *
     * @return void
     */

    public function getCartCount()
    {
        $carts = Cart::where('customer_id',auth()->guard('api')->user()->id)->get()->count();
        
        return response()->json([
            'success' => true,
            'message' => 'Total Cart Count ',
            'cartCount'   => $carts
        ]);
    }

    /**
     * 
     * 
     * Undocumented function
     *
     * @return void
     */

    public function getCartTotal()
    {
        $carts = Cart::with(['products'])->where('customer_id',auth()->guard('api')->user()->id)
                ->latest()
                ->sum('price');
        
        return response()->json([
            'success' => true,
            'message' => 'Total Cart Price ',
            'total'   => $carts
        ]);
    }

    /**
     * 
     * 
     * 
     *  
     */

     public function getCartTotalWeight()
     {
        $carts = Cart::with(['products'])
        ->where('customer_id',auth()->guard('api')->user()->id)
        ->latest()
        ->sum('weight');

        return response()->json([
            'success' => true,
            'message' => 'Total Cart Price ',
            'total'   => $carts
        ]);
     }
     

     /**
      *
      *  
      *
      *
      */

      public function removeCart(Request $request)
      {
        $cart = Cart::where('customer_id',auth()->guard('api')->user()->id)
                    ->where('id',$request->cart_id)
                    ->first();

        if($cart){
            $cart->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Remove Item Cart',
            'data' => $request->cart_id
        ]);
      }

      /**
       * Undocumented function
       *
       * @param Request $request
       * @return void
       */

      public function removeAllCart(Request $request)
      {
          $cart = Cart::where('customer_id',auth()->guard('api')->user()->id)
                        ->delete();
            
        return response()->json([
            'success' => true,
            'message' => 'Remove All Cart',
        ]);

      }
}
