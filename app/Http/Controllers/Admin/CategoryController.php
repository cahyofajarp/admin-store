<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('created_at','DESC')->get();

        return view('pages.admin.category.index',compact(
            'categories',
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name' => 'required',
            'title_category' => 'required',
            'image' => 'required|image|max:102400|mimes:png,jpg',
            'text_desc_category' => 'required',
        ]);
        
        try{
            $image = null;

            if($request->hasFile('image')){
                $image = $request->file('image')->store(
                    'categories_image','public'
                );
            }

            $category = Category::create([
                'name' => ucwords($request->name),
                'title_category' => $request->title_category,
                'image' => $image,
                'text_desc_category' => $request->text_desc_category,
                'slug' => Str::slug($request->name)
            ]);

            return response()->json([
                'success' => true
            ]);

        }catch(\Exception $e){
            // return response()->json([
            //     'error' => $e->getMessage()
            // ]);
        }
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
    public function edit(Category $category)
    {
        return response()->json([
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {   

        $this->validate($request,[
            'name' => 'required',
            'title_category' => 'required',
            'image' => 'image|max:102400|mimes:png,jpg',
            'text_desc_category' => 'required',
        ]);
        
        $image = $category->image;
        

        if($request->hasFile('image')){
           
            if($category->image){
                Storage::disk('local')->delete('public/categories_image/'.basename($category->image));
           }

            $image = $request->file('image')->store(
                'categories_image','public'
            );
        }
        
        if($category){
            $category->update([
                'name' => ucwords($request->name),
                'title_category' => $request->title_category,
                'image' => $image,
                'text_desc_category' => $request->text_desc_category,
                'slug' => Str::slug($request->name)
            ]);
    
            return response()->json([
                'success' => true,
            ]);
        }

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if($category){
            $category->delete();

            return response()->json([
                'success' => true
            ]);
        }

        return false;
    }
}
