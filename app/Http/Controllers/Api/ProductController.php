<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductGalleries;
use App\Models\productgallery_thumbnail;
use App\Models\Size;
use App\Models\Stock;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {      
        // $products = Product::with(['flashsale_products','productgallery_thumbnails.product_gallery' => function($q) {
        //     $q->select('id','image');
        // }])->latest()->get();
        
        
        // $categories = Category::latest()->limit(5)->get();

        // $sizes = Size::latest()->get();
        
        // $colors = Color::latest()->get();

        // // // // dd($categories);
        // return response()->json([
        //     'success'   => true,
        //     'message'   => 'List Data Products',
        //     'products'  => $products,
        //     'categories' => $categories,
        //     'sizes'  => $sizes,
        //     'colors'  => $colors,
        // ], 200);
    }
 
    public function show($slug)
    {
        
       if($slug){
            $product = Product::with(['flashsale_products.flashsale' => function($q){
                
                $q->select('id','title','status')->where('status','ongoing');
                
            },'categories','colors','sizes','productgallery_thumbnails','productgallery_thumbnails.product_gallery' => function($q) {
                
                $q->select('id','image');
            
            }])->where('slug',$slug)->first();
                
            $product_galleries = ProductGalleries::where('color_id',$product->productgallery_thumbnails->color_id)->where('product_id',$product->id)->get();

            $stock = Stock::where('product_id',$product->id)
            ->where('color_id',$product->productgallery_thumbnails->color_id)
            ->where('size_id',$product->sizes->first()->id)
            ->first();
            
            $productRelated = Product::with(['categories' =>function($q) {
                
                $q->select('categories.id','slug');
            
            },'productgallery_thumbnails.product_gallery' => function($q) {
               
                return $q->select('id','image');
            
            }])
            ->whereHas('categories',function($q) use ($product){
                
                $q->whereIn('.categories.id',$product->categories->pluck('id'));
            })
            ->where('slug','<>',$product->slug)->latest()->limit(4)->get();
            
            // dd($productRelated);
            if($product){
                return response()->json([
                    'success'   => true,
                    'message'   => 'List Data Product '. $product->title,
                    'product'  => $product,
                    'product_galleries' => $product_galleries,
                    'related' => $productRelated,
                    'stock' => $stock
                ], 200);
            }
            else{
                return response()->json([
                    'success'   => false,
                    'message' => 'List products - '. ucwords(str_replace('-',' ',$slug)) .' -  Tidak di temukan',
                    // 'products'  => $product
                ], 404);
            }
       }
    }

    public function changeColor($slug,$color_id,$size_id)
    {

        $product = Product::with(['colors','sizes'])->where('slug',$slug)->first();
        
        $product_galleries = ProductGalleries::where('color_id',$color_id)->where('product_id',$product->id)->get();
        
        $stock = Stock::where('product_id',$product->id)
            ->where('size_id',$size_id)
            ->where('color_id',$color_id)->first();
        
        return response()->json([
            'success' => true,
            'product' => $product,  
            'product_galleries' => $product_galleries,
            'stock' => $stock,
        ]);
    }

    public function filterColor(Request $request)
    {
        
        $price          = $request->input('price');
        
        $categories     = Category::latest()->limit(6)->get();

        $sizes          = Size::orderBy('sizes_name')->get();
        
        $colors         = Color::latest()->get();
        
        $products       = Product::select('*',DB::raw('COALESCE(price_after_flashsale, price_after_discount,price) as prices'))
        ->with(['flashsale_products.flashsale' => function($q){
                
            $q->select('id','title','status')->where('status','ongoing');
            
        },'categories' => function($q) {

            return $q->select('categories.id','slug');
        } 
        ,'colors' => function($q) {
            return $q->select('colors.id');
        }
        ,'sizes' => function($q) {
            return $q->select('sizes.id');
        }
        ,'productgallery_thumbnails.product_gallery' => function($q) {
            return $q->select('id','image');
        }])
        ->whereHas('categories', function($q) use ($request){
            return $request->category_slug ? $q->where('slug',$request->category_slug) : '';
        })
        ->whereHas('colors', function($q) use ($request){
            return $request->colors ? $q->whereIn('colors.id',$request->colors) : '';
        })
        ->whereHas('sizes',function($q) use ($request){
            return $request->sizes ? $q->whereIn('sizes.id',$request->sizes) : '';
        })
        ->when($price['from_price'] != null && $price['to_price'] != null , function ($q) use ($request){
            return $q->havingBetween('prices',[(int) $request->price['from_price'],(int) $request->price['to_price']]);
        })
        ->when($price['from_price'] != null,function ($q) use ($request){
            return $q->having('prices','>=',(int) $request->price['from_price']);
        })
        ->when($price['to_price'] != null,function ($q) use ($request){
            return $q->having('prices','<=',(int) $request->price['to_price']);
        })
        // ->get();
        ->latest()->paginate(4);
        
        return response()->json([
            'success'       => true,
            'message'       => 'List Data Products',
            'products'      => $products,
            'categories'    => $categories,
            'data'          => $price,
            'sizes'         => $sizes,
            'colors'        => $colors,
        ], 200);
    }

    public function productHome()
    {
        
        $product_home = Product::with(['flashsale_products.flashsale' => function($q){
                
            $q->select('id','title','status')->where('status','ongoing');
            
        },'productgallery_thumbnails.product_gallery' => function($q) {
            $q->select('id','image');
        }])
        ->latest()->limit(8)->get();
        
        return response()->json([
            'success'   => true,
            'message'   => 'List Data Products',
            'product_home'  => $product_home,
        ], 200);
    }


    public function productSearch(Request $request)
    {
        $categories = [];
        $products   = [];

        if($request->search){
            $products = Product::with(['categories'])
                        ->whereHas('categories',function($q) use ($request){
                            return $q->where('name','like','%'. $request->search.'%');
                        })
                        ->orWhere('title','like','%'. $request->search.'%')
                        ->latest()
                        ->limit(4)
                        ->get();

            $categories = Category::with(['products' => function ($q) {
                            $q->select('products.id','products.title');
                        }])
                        ->whereHas('products',function($q) use ($request){
                            return $q->where('title','like','%'. $request->search.'%');
                        })
                        ->orWhere('name','like','%'. $request->search.'%')
                        ->latest()
                        ->limit(4)
                        ->get();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Data semua product berdasarkan data pencaharian : '.$request->search,
            'products' => $products,
            'categories' => $categories 
        ]);
    }

    
}
