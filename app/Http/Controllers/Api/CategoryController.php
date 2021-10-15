<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List data category',
            'categories' => $categories
        ]);
    }

    public function show($slug)
    {
        
        $category = Category::where('slug',$slug)->first();
        
        $products = Product::with(['categories','productgallery_thumbnails.product_gallery' => function($q) {
            $q->select('id','image');
        }])
        ->whereHas('categories',function($q) use ($slug) {
            $q->where('slug',$slug);
        })
        ->latest()->get();
        
        
        $categories = Category::latest()->limit(5)->get();
        
        $sizes = Size::latest()->get();
        
        $colors = Color::latest()->get();
        

        if($category){
            return response()->json([
                'success' => true,
                'message' => 'List products By Category : '.$category->name,
                'products' => $products,
                'categories' => $categories,
                'sizes'  => $sizes,
                'colors'  => $colors,
            ],200);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'List products By Category : '. ucwords(str_replace('-',' ',$slug)) .' Tidak di temukan',
                // 'products' => $category->products()->latest()->get()
            ],404);
        }
    }

    public function categoryHome()
    {   

        $categories = Category::latest()->skip(1)->limit(4)->get();

        $bigCategory = Category::latest()->first();

        return response()->json([
            'success' => true,
            'message' => 'List data category',
            'categories' => $categories,
            'big_category' => $bigCategory
        ]);
    }
}
