<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $sliders = Slider::orderBy('created_at','DESC')->get();

        return view('pages.admin.sliders.index',compact(
            'sliders'
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
        // dd($request->all());

        $this->validate($request,[
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240'
        ]);

        $slider = null;
            
        if($request->hasFile('image')){
            $slider = $request->file('image')->store('sliders','public');
        }

        Slider::create([
            'title' => $request->title,
            'image' => $slider

        ]);

        return redirect()->route('admin.sliders');
        
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
    public function edit(Slider $slider)
    {
        return response()->json([
            'slider' => $slider
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider)
    {
        $this->validate($request,[
            'title' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:10240'
        ]);
        
        $image = $request->image ? $request->image : $slider->image;
        
        if($request->hasFile('image')){
            if($slider->image){
                Storage::disk('local')->delete('public/sliders/'.basename($slider->image));
            }

            $image = $request->file('image')->store('sliders','public');
        }
        
        $slider->update([
            'title' => $request->title,
            'image' => 'sliders/'.basename($image),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        if($slider){
            
            Storage::disk('local')->delete('public/sliders/'.basename($slider->image));
            
            $slider->delete();
            
            return response()->json([
                'success' => true,
            ]);
        }
    }
}
