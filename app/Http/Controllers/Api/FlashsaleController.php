<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flashsale;
use App\Models\Cart;
use App\Models\FlashsaleProduct;
use Illuminate\Http\Request;

class FlashsaleController extends Controller
{  
    
    public function __construct()
    {
        $this->middleware(['auth:sanctum','verified-api'])->except('index');
    }
    public function index()
    {

        $flashsaleMaster = Flashsale::latest()->where('status','ongoing')->first();

        if($flashsaleMaster){
            $flashsales = FlashsaleProduct::with(['flashsale' => function($q) use($flashsaleMaster){
                // $q->select('id','title');
                $q->where('id',$flashsaleMaster->id);
    
            },'product','product.productgallery_thumbnails.product_gallery' => function($q) {
                
                return $q->select('id','image');
    
            }])->orderBy('created_at','DESC')->limit(8)->get();
    
            $countDown = strtotime($flashsaleMaster->end_flashsale) - strtotime($flashsaleMaster->start_flashsale);
            $now = \Carbon\Carbon::now();
            
            $status = null;
    
            if($now > $flashsaleMaster->end_flashsale){
                $countDown = 0;
                $status = 'done';
            }
            else if($now < $flashsaleMaster->end_flashsale && $now > $flashsaleMaster->start_flashsale){
                $status = 'ongoing';
            }
            else if($now < $flashsaleMaster->start_flashsale){
                $status = 'pending';
            }
            
            return response()->json([
                'success' => true,
                'flashsales' => $flashsales,
                'flashsale_master' => $flashsaleMaster,
                'message' => 'Data flahsale Product',
                'countDown' => $countDown,
                'status' => $status
            ],200);
        }


    }

    
     /**
      *
      * Cart Delete when flashsale had been finished  
      * 
      *
      */

      public function cartDeleteFlashsale(Request $request)
      {
        $success = false;
        $message = null;
        $status = null;

        $flashsaleMaster = Flashsale::latest()->first();

        $now = \Carbon\Carbon::now();

        if($flashsaleMaster){
            
            $flashsale_deal_product = FlashsaleProduct::with(['product'])->where('flashsale_id',$flashsaleMaster->id);
            
            if($now > $flashsaleMaster->end_flashsale){
                // $countDown = 0;
                $flashsaleMaster->update([
                    'status' => 'done',
                ]);
                
                $status = 'done';
                
                $cart = Cart::with(['product.flashsale_products.flashsale' => function($q){
                    
                    $q->select('id','title');
                }])
                ->whereHas('product.flashsale_products.flashsale',function($q) use ($flashsaleMaster) {
                    $q->where('id',$flashsaleMaster->id);
                })
                ->where('customer_id',auth()->guard('api')->user()->id);
    
                if($cart->get()->count() > 0){
                    $success = true;
                    $message = 'flashsale had been finished, Success deleted flashsale product.';
                    
                    $cart->delete();
                }

                foreach($flashsale_deal_product->get() as $productData){
                    $productData->product->update([
                        'price_after_flashsale' => null,
                    ]);
                }
                
                $flashsale_deal_product->delete();
            } 
            
            else if($now < $flashsaleMaster->end_flashsale && $now > $flashsaleMaster->start_flashsale){
                $status = 'ongoing';
            }
            else if($now < $flashsaleMaster->start_flashsale){
                $status = 'pending';
            }
        }
            
        return response()->json([
            'success' => $success,
            'message' => $message,
            'flashsales' => [],
            'flashsale_master' => [],
            'countDown' => 0,
            'status' => $status

        ]);
      }
}
