<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HightlightProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HightlightProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->get();
        
        $product_highlight = HightlightProduct::with(['product'])->latest()->get();
        if($product_highlight->count() > 0){
            if($product_highlight[0]->product->discount == 0){
                return view('pages.admin.hightlight_product.index',compact(
                    'products',
                    'product_highlight'
                ))->with('warning','Product ini belum ada discount silahkan pergi ke halaman product untuk edit discount.');
            }
        }
        
        return view('pages.admin.hightlight_product.index',compact(
            'products',
            'product_highlight'
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
            'description' => 'required',
            'product_id' => 'required|exists:products,id',
            'image' => 'image|mimes:png,jpg,svg,jpeg|max:1048576'
        ]);

        $data = $request->all();
        $image = $request->file('image')->store(
            'hightlight','public'
        );
        
        $data['image'] = $image;

        $hightlight =  HightlightProduct::create($data);

        return response()->json([
            'success' => true,
            'message' => 'success add hightlight product'
        ]);
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
    public function edit(HightlightProduct $hightlight)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data edit Product Hightlight'.$hightlight->title,
            'data' => $hightlight
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HightlightProduct $hightlight)
    {
        $this->validate($request,[
            'title' => 'required',
            'description' => 'required',
            'product_id' => 'required|exists:products,id',
            'image' => 'mimes:png,jpg,svg,jpeg|max:1048576'
        ]);


        $data = $request->all();

        // $image = $request->image ? $request->image : $product->image;
        
        
        $data['image'] = 'hightlight/'.basename($hightlight->image);

        if($request->hasFile('image')){
            
            if($hightlight->image){
                Storage::disk('local')->delete('public/hightlight/'.basename($hightlight->image));
            }
            
            $data['image'] = $request->file('image')->store(
                'hightlight','public'
            );
        }

        $product_highlight = $hightlight->update($data);

        
        return response()->json([
            'success' => true,
            'message' => 'success update hightlight product'
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(HightlightProduct $hightlight)
    {
        if($hightlight->image){
            Storage::disk('local')->delete('public/hightlight/'.basename($hightlight->image));
        }

        $hightlight->delete();

        return response()->json([
            'success' => true,
            'message' => 'success deleted hightlight product'
        ]);

    }
}
