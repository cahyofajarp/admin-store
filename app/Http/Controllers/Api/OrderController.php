<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum','verified-api']);
    }
        
    public function index(Request $request)
    {
        // $invoices = Invoice::with(['orders' => function($q){
        //     return $q->latest();
        // },'orders.product','orders.product_gallery' => function($q) {
        //     return $q->select('id','image');
        // }])
        // ->where('customer_id',auth()->guard('api')->user()->id)->latest()
        // ->paginate(2);
        
        // return response()->json([
        //     'success' => true,
        //     'message' => 'List Invoices : '.auth()->guard('api')->user()->name,
        //     'data' => $invoices,
        //     // 'order_date' => \Carbon\Carbon::parse($invoices->created_at)->isoFormat('dddd, D MMM Y HH:mm'),

        // ],200);

        $data = true;

        $invoices = null;
        $message  = null;
        $success  = false; 
        $code     = 200; 
        
        if(!$request->from_date && !$request->to_date){
            $invoices = Invoice::with(['orders' => function($q){
                
                return $q->latest();

            },'orders.product','orders.product_gallery' => function($q) {
                
                return $q->select('id','image');
            
            }])
            ->where('customer_id',auth()->guard('api')->user()->id)
            ->latest()
            ->paginate(2);

            $message = 'List Invoices : '.auth()->guard('api')->user()->name;
            $success = true;
       }
      

       else if($request->from_date || $request->to_date){
            
            if(!$request->from_date){
                $data = false;
                $message = "Silahkan Pilih from_date";
                $success = false;
                $invoices = [];
                $code = 422;
            }
            
            else if($request->to_date.' 23:59:59' < $request->from_date.' 00:00:00' && $request->to_date && $request->from_date){
                $data = false;

                $message = "Data to_date don't greather than from_date";
                $success = false;   
                $invoices = [];
                $code = 422;
            }

            else if($data == true){
                $invoices = Invoice::with(['orders' => function($q){
                    return $q->latest();

                
                },'orders.product','orders.product_gallery' => function($q) {
                    
                    return $q->select('id','image');
                
                }])
                ->where('customer_id',auth()->guard('api')->user()->id)
                ->whereBetween('created_at',[$request->from_date." 00:00:00",$request->to_date." 23:59:59"])
                ->orWhereBetween('created_at',[$request->from_date." 00:00:00",$request->from_date." 23:59:59"])
                ->latest()
                ->paginate(2);

                
                $message = 'List Invoices : '.auth()->guard('api')->user()->name;
                $success = true;
            }
           
       }
       
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $invoices,
            // 'order_date' => \Carbon\Carbon::parse($invoices->created_at)->isoFormat('dddd, D MMM Y HH:mm'),
        ],$code);
    }

    public function show($snap_token)
    {
        
        $invoices = Invoice::with(['orders','orders.color','orders.size','orders.product.colors','orders.product_gallery' => function($q) {
            return $q->select('id','image');
        }])->where('customer_id',auth()->guard('api')->user()->id)
                    ->where('snap_token',$snap_token)->first();
        
        return response()->json([
            'success' => true,
            'message' => 'List Invoices : '.auth()->guard('api')->user()->name,
            'data' => $invoices,
            'order_date' => \Carbon\Carbon::parse($invoices->created_at)->isoFormat('dddd, D MMM Y HH:mm'),
            // 'product' => $invoices->orders,
            'totalOrder' => $invoices->orders->sum('price')

        ],200);
    }

    public function orderSearch(Request $request)
    {
        $data = true;

        $invoices = null;
        $message  = null;
        $success  = false; 
        $code     = 200; 
        
        if(!$request->from_date && !$request->to_date){
            $invoices = Invoice::with(['orders' => function($q){
                
                return $q->latest();

            },'orders.product','orders.product_gallery' => function($q) {
                
                return $q->select('id','image');
            
            }])
            ->where('customer_id',auth()->guard('api')->user()->id)
            ->latest()
            ->paginate(2);

            $message = 'List Invoices : '.auth()->guard('api')->user()->name;
            $success = true;
       }
      

       else if($request->from_date || $request->to_date){
            
            if(!$request->from_date){
                $data = false;
                $message = "Silahkan Pilih from_date";
                $success = false;
                $invoices = [];
                $code = 422;
            }
            
            else if($request->to_date.' 23:59:59' < $request->from_date.' 00:00:00' && $request->to_date && $request->from_date){
                $data = false;

                $message = "Data to_date don't greather than from_date";
                $success = false;   
                $invoices = [];
                $code = 422;
            }

            else if($data == true){
                $invoices = Invoice::with(['orders' => function($q){
                    return $q->latest();

                
                },'orders.product','orders.product_gallery' => function($q) {
                    
                    return $q->select('id','image');
                
                }])
                ->where('customer_id',auth()->guard('api')->user()->id)
                ->whereBetween('created_at',[$request->from_date." 00:00:00",$request->to_date." 23:59:59"])
                ->orWhereBetween('created_at',[$request->from_date." 00:00:00",$request->from_date." 23:59:59"])
                ->latest()
                ->paginate(2);

                
                $message = 'List Invoices : '.auth()->guard('api')->user()->name;
                $success = true;
            }
           
       }
       
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $invoices,
            // 'order_date' => \Carbon\Carbon::parse($invoices->created_at)->isoFormat('dddd, D MMM Y HH:mm'),
        ],$code);
    }
}
