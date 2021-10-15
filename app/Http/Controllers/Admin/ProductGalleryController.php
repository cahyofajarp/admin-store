<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductGalleries;
use App\Models\productgallery_thumbnail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $categories = Category::orderBy('created_at','DESC')->get();
        $products = Product::with(['productGalleries'])->orderBy('created_at','DESC')->get();

        return view('pages.admin.product_gallery.index',compact(
            'products',
            // 'categories'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function color(Product $product)
    {  
        $productColor = Product::with(['colors'])->where('id',$product->id)->first();   

        // dd($product->productGalleries->groupBy('product_id')->first()[0]);

        $productGal = ProductGalleries::where('product_id',$product->id)->first();
        // $col = Color::where('id',$productGal->color_key)->first();

        return view('pages.admin.product_gallery.color-product-gallery',compact(
            'product',
            'productColor',
            // 'col'
            // 'colors'
        ));
        
    }    
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create(Product $product,Color $color)
   {  
       
       $sizeColor = Product::where('id',$product->id)->with(['colors','sizes'])->first();

        return view('pages.admin.product_gallery.create',compact(
        'product',
        'sizeColor',
        'color'

       ));
   }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Product $product,Color $color)
    {
        $this->validate($request,[
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240'
        ]);

        $image = null;

        if($request->hasFile('image')){
            
            $image = $request->file('image')->store('product_galleries','public');
        }

        $gallery = ProductGalleries::create([
            'title' => $request->title,
            'product_id' => $product->id,
            'image' => $image,
            'color_id' => $color->id
        ]);

        if($gallery){
            return response()->json(['success' => true]);
        }
        else{
            return response()->json(['success' => false]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product,Color $color)
    {
        $productGallery = ProductGalleries::where('color_id',$color->id)->where('product_id',$product->id)->get();

        return view('pages.admin.product_gallery.show',compact(
            'product',
            'productGallery',
            'color'
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function colorKeyGallery(Request $request , Product $product)
    {
        $this->validate($request,[
            'color_key' => 'required|exists:colors,id'
        ]);

        $productGallery = ProductGalleries::where('product_id',$product->id);

        $productGallery->update([
            'color_key' => $request->color_key
        ]);

        if($productGallery){
            return response()->json(['success' => true]);
        }
        else{
            return response()->json(['success' => false]);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createThumbnail(Request $request,Product $product,Color $color)
    {

        
        $productGallery = productgallery_thumbnail::where('product_id',$product->id)->first();


       if($productGallery){
            $productGallery->update([
                'product_galleries_id' => $request->product_galleries_id,
                'color_id' => $color->id,
                'product_id' => $product->id
            ]);
       }
       else{
            $productGallery = productgallery_thumbnail::create([
                'color_id' => $color->id,
                'product_id' => $product->id,
                'product_galleries_id' => $request->product_galleries_id
            ]);
       }

        if($productGallery){
            return response()->json(['success' => true]);
        }
        else{
            return response()->json(['success' => false]);
        }
    }

   /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductGalleries $gallery)
    {
        if($gallery){
            if($gallery->image){
                Storage::disk('local')->delete('public/product_galleries/'.basename($gallery->image));
            }
            $gallery->delete();

            return response()->json(['success' => true]);
        }
    }
}
