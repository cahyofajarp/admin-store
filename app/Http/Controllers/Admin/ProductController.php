<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {   

        $colors = Color::all();
        $sizes = Size::all();
        $categories = Category::orderBy('created_at','DESC')->get();
        $products = Product::orderBy('created_at','DESC')->get();

        return view('pages.admin.product.index',compact(
            'products',
            'categories',
            'colors',
            'sizes'
        ));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            'category_id' => 'required|exists:categories,id', 
            'content' => 'required',
            'weight' => 'required',
            'price' => 'required',
            'discount' => 'required',
            'end_of_discount' => Rule::requiredIf( function () use ($request){
                return (int) $request->input('discount') != 0;
            }),
            'end_if_discount' => 'date|after:'.\Carbon\Carbon::now(),
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id'
        ]);
        

        $price_after_discount = $request->discount ? $request->price - ($request->discount / 100) * $request->price : null;

        $product = Product::create([
            'slug' => Str::slug($request->title),
            'title' =>  $request->title,
            'content' =>  $request->content,
            'weight' =>  (int) $request->weight,
            'price' =>  (int)  $request->price,
            'price_after_discount' => (int) $price_after_discount,
            'end_of_discount' =>(int) $request->discount == 0 ? null : $request->end_of_discount,
            'discount' =>  (int) $request->discount,
            
        ]);

        $product->colors()->sync($request->color_id);
        
        $product->sizes()->sync($request->size_id);

        $product->categories()->sync($request->category_id);


        if($product){
            return response()->json([
                'success' => true,
            ]);
        }else{
            return response()->json([
                'success' => false,
            ]);

        }
    }

    /**
     * create Stock by product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createStock(Product $product)
    {
        
        $sizeColor = Product::where('id',$product->id)->with(['colors','sizes'])->first();

        $stocks = Stock::where('product_id',$product->id)
        ->with(['product','color','size'])->latest()->get();

        return view('pages.admin.product.stock',compact(
            'product',
            'stocks',
            'sizeColor'
        ));
    }
    /**
     * create Stock by product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeStock(Request $request,Product $product)
    {
        $this->validate($request,[
            'stock' => 'required|numeric',
            'color_id' => 'required|exists:colors,id',
            'size_id' => 'required|exists:sizes,id',
        ]);

        $stockProduct = Stock::where('product_id',$product->id)->first();

        if($stockProduct){
            if($stockProduct->color_id == $request->color_id && $stockProduct->size_id == $request->size_id){
                return response()->json([
                    'success' => false,
                    'message' => 'Stock pada product - size - color sudah terdaftar!' 
                ]);
            }
        }

        $data = $request->all();
        $data['slug'] = Str::random(10);
        $data['product_id'] = $product->id;

        $stock = Stock::create($data);

        if($stock){

            return response()->json([
                'success' => true,
                'message' => 'Success added stock in database' 
            ]);
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editStock(Product $product,Stock $stock)
    {
        if($stock){


            return response()->json([
                'success' => true,
                'message' => 'Success deleted stock in database',
                'stock' => $stock, 
            ]);

        }
    } /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function updateStock(Request $request,Product $product,Stock $stock)
   {
        $this->validate($request,[
            'stock' => 'required|numeric',
            'color_id' => 'required|exists:colors,id',
            'size_id' => 'required|exists:sizes,id',
        ]);

        $stockProduct = Stock::where('product_id',$product->id)
        ->where('size_id','<>',$stock->size_id)
        ->orWhere('color_id','<>',$stock->color_id)->get();
        
        foreach($stockProduct as $st){
            if($st->color_id == $request->color_id && $st->size_id == $request->size_id){
                return response()->json([
                    'success' => false,
                    'message' => 'Stock pada product - size - color sudah terdaftar!' 
                ]);
            }
        }
        
        $data = $request->all();

        $stock->update($data);

        if($stock){
            return response()->json([
                'success' => true,
                'message' => 'Success updated stock in database' 
            ]);
        }

   }     
    
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function deleteStock(Product $product,Stock $stock)
   {
       if($stock){
           $stock->delete();

           return response()->json([
               'success' => true,
               'message' => 'Success deleted stock in database' 
           ]);

       }
   } 
    
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show(Product $product)
   {
       return view('pages.admin.product.preview',compact(
           'product'
       ));
   }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        
        $categoryByProduct = Category::with(['products'])
        ->whereHas('products',function($q) use ($product){
            $q->where('product_id',$product->id);
        })
        ->get();
        
        $categories = Category::orderBy('created_at','DESC')->get();

        $productColorSize = Product::with(['colors','sizes'])->where('id',$product->id)->first();

        return response()->json([
            'product' => $product,
            'categoryByProduct' => $categoryByProduct,
            'categories' => $categories,
            'productColorSize' => $productColorSize
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request,[
            'category_id' => 'required|exists:categories,id',
            'title' => 'required',
            'content' => 'required',
            'weight' => 'required',
            'price' => 'required',
            'discount' => 'required',
            'end_of_discount' => Rule::requiredIf( function () use ($request){
                return (int) $request->input('discount') != 0;
            }),
            'end_if_discount' => 'date|after:'.\Carbon\Carbon::now(),
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id'
        ]);

        if($product){
            
            $price_after_discount = $request->discount ? $request->price - ($request->discount / 100) * $request->price : null;
            $product->update([
                'slug' => Str::slug($request->title),
                'title' =>  $request->title,
                'content' =>  $request->content,
                'weight' =>  $request->weight,
                'end_of_discount' => (int) $request->discount == 0 ? null : $request->end_of_discount,
                'price' =>  $request->price,
                'discount' =>  $request->discount,
                'price_after_discount' => $price_after_discount
            ]); 
            
            $product->categories()->sync($request->category_id);
            
            $product->colors()->sync($request->color_id);
            
            $product->sizes()->sync($request->size_id);
                
            return response()->json([
                'success' => true,
            ]);
        }else{
            return response()->json([
                'success' => false,
            ]);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if($product){
            $product->delete();

            return response()->json([
                'success' => true,
            ]);
        }else{
            return response()->json([
                'success' => false,
            ]);

        }
    }
}
