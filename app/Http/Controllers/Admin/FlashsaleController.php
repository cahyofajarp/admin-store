<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Flashsale;
use App\Models\FlashsaleProduct;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlashsaleController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $flashsale_end = Flashsale::latest()->first();

        $showButtonCreate = false;
        
        $now = \Carbon\Carbon::now();

        if($flashsale_end){
            if($now > $flashsale_end->end_flashsale){
                $showButtonCreate = true;
            }
        }
        
        $flashsales = Flashsale::with(['flashsale_deal.product'])->orderBy('created_at','DESC')->get();

        return view('pages.admin.flashsale.index',compact(
            'flashsales',
            'showButtonCreate',
            'flashsale_end'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $isShow = false;
        $flashsale_end = Flashsale::where('status','pending')->latest()->first();

        $now = \Carbon\Carbon::now();

        if($flashsale_end){
            if($now < $flashsale_end->end_flashsale){
                return redirect()->route('admin.flashsale');
            }
            else{
                $isShow = true;
            }
        }
        else{

            $isShow=true;
            
            if($isShow){
                $products = Product::orderBy('created_at','DESC')->get();
                if(request()->ajax()){
    
                    $products = Product::with(['flashsaleProduct','productGalleries'])->orderBy('created_at','DESC')->get();
                    return response()->json(['products' => $products]);
                }
                
                return view('pages.admin.flashsale.create',compact(
                    'products'
                ));
            }
           
        }
    } 
    
    /**
    * Show the form for product view of flashsale
    *
    * @return \Illuminate\Http\Response
    */
   public function product(Flashsale $flashsale)
   {
    //    $products = Flashsale::with(['flashsale_deal','flashsale_deal.product'])->where('id',$flashsale->id)->orderBy('created_at','DESC')->first();
        
        $products = FlashsaleProduct::with(['product'])->where('flashsale_id',$flashsale->id)->get();

        return response()->json(['products_flashsale' => $products,'flashsale' => $flashsale]);
   }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
        $this->validate($request,[
            'title' => 'required',
            'description' => 'required',
            'start_flashsale' => 'required|date|after:'.\Carbon\Carbon::now(),
            'end_flashsale' => 'required|date|after:start_date',
            'banner' => 'image',
            'product_id' =>'required|exists:products,id',
            'discount' => 'required'
        ]);

        
        DB::beginTransaction();
        
        $collection = collect($request->discount);

        $filter = $collection->filter(function ($fil,$val){
            return $fil != null;
        });
        

        $flashsale = Flashsale::create([
            'slug' => Str::random(10),
            'title' => $request->title,
            'description' => $request->description,
            'start_flashsale' => \Carbon\Carbon::parse($request->start_flashsale),
            'end_flashsale' => \Carbon\Carbon::parse($request->end_flashsale),
            'status' => 'pending',
        ]);
        
        for($i = 0; $i < count($request->product_id); $i++){
            FlashsaleProduct::create([
                'flashsale_id' =>  $flashsale->id,
                'product_id' => (int) $request->product_id[$i],
                'discount' => $filter->values()[$i]
            ]);

          
        }
        
        DB::commit();

        return response()->json(['success' => true]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Flashsale $flashsale)
    {

        
        $products = Product::with(['flashsaleProduct'=> function($q) use ($flashsale){
            
            return $q->where('flashsale_id',$flashsale->id);

        },'productGalleries'])->orderBy('created_at','DESC')->get();
        
        if(request()->ajax()){
            return response()->json([
                'products' => $products,
                'flashsale' => $flashsale
            ]);
        }

        return view('pages.admin.flashsale.edit',compact(
            'flashsale',
            'products'
        ));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request,$flashsale)
    {
        $product = null;

        if($request->search){
            $products = Product::with(['flashsaleProduct'=> function($q) use ($flashsale){
            
                return $q->where('flashsale_id',$flashsale);
    
            },'productGalleries'])->where('title', 'like', '%' . $request->search . '%')->orderBy('created_at','DESC')->get();
       
        }else{
            $products = Product::with(['flashsaleProduct' => function($q) use ($flashsale){
            
                return $q->where('flashsale_id',$flashsale);
    
            },'productGalleries'])->orderBy('created_at','DESC')->get();

        }

        return response()->json(['products' => $products]);
    }

    public function searchCreate(Request $request)
    {
        if($request->search){
            $products = Product::with(['productGalleries'])->where('title', 'like', '%' . $request->search . '%')->orderBy('created_at','DESC')->get();
       
        }else{
            $products = Product::with(['productGalleries'])->orderBy('created_at','DESC')->get();

        }

        return response()->json(['products' => $products]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Flashsale $flashsale)
    {

        $collection = collect($request->discount);

        $filter = $collection->filter(function ($fil,$val){
            return $fil != null;
        });

        $this->validate($request,[
            // 'title' => 'string',
            // 'description' => 'string',
            'start_flashsale' => 'required|date|after:'.\Carbon\Carbon::now(),
            'end_flashsale' => 'required|date|after:start_date',
            'banner' => 'image',
            // 'discount.*' => function($attribute, $value, $fail) use ($request,$filter){
            //     if(count($request->product_id) != count($filter)){
            //         $fail('The '.$attribute . 'is not null if product is checked.');
            //     }
            // },
        ]);

        DB::beginTransaction();

            $flashsale->update([
                'title' => $request->title,
                'description' => $request->description,
                'start_flashsale' => \Carbon\Carbon::parse($request->start_flashsale),
                'end_flashsale' => \Carbon\Carbon::parse($request->end_flashsale),
                // 'status' => 'pending',
            ]);
            

            // $notInArray2 = [];
            // $array1 = [3,5,2];
            // $array2 = [5,2];
            // foreach($array1 as $array1item){
                
            //     $any = false;
                
            //     foreach($array2 as $array2item){
            //         if($array1item == $array2item){
            //             $any = true;
            //             // print_r($array1item);
            //         }
            //     }

            //     if(!$any){
            //         $notInArray2[] = $array1item;
            //     }
            // }

            // print_r($notInArray2);
            
            $flashsale_p = FlashsaleProduct::where('flashsale_id',$flashsale->id)
                            ->get();
            
            if($request->product_delete){
                
                // $a1 = $flashsale_p->pluck('product_id')->toArray();

                $a2 = (array) $request->product_delete;

                // $result = array_values(array_diff($a1,$a2));

                    foreach($a2 as $val){
                    
                        $flashDel = FlashsaleProduct::where('flashsale_id',$flashsale->id)->where('product_id',$val)->first();

                        if($flashDel){
                            $flashDel->delete();
                        }
                    
                    }
            }

             if($request->product_id){
                
                
                foreach($request->product_id as $key => $product_id){
                    
                    $count = FlashsaleProduct::where('flashsale_id',$flashsale->id)
                    ->where('product_id',$product_id)->get();
                    
                    
                    if($filter->values()[$key] == null){
                        return;
                    }
                    
                    if($count->count() == 0){
                    
                        // echo $product_id;
                        FlashsaleProduct::create([
                            'flashsale_id' =>  $flashsale->id,
                            'product_id' => (int) $product_id,
                            'discount' => $filter->values()[$key]
                        ]);
                    }

                    else if($count->count() == 1){

                        $count->first()->update([
                            'product_id' => (int) $product_id,
                            'discount' => $filter->values()[$key],
                        ]);
                    } 

                }   

            }
            
        DB::commit();
        
        return response()->json(['success' => true,'code' => 200]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
