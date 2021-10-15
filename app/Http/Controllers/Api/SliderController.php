<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();
        
        return response()->json([
            'success' => true,
            'message' => 'Data Slider',
            'sliders' => $sliders
        ]);
    }
}
