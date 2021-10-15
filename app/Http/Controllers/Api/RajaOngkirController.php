<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;
use Kavist\RajaOngkir\Facades\RajaOngkir;

class RajaOngkirController extends Controller
{   

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function getProvince()
    {
        $provinces = Province::all();

        return response()->json([
            'success' => true,
            'message' => 'List Data Provinces',
            'data' => $provinces,
        ]);
    }

    public function getCities(Request $request)
    {
        $city = City::where('province_id',$request->province_id)->get();

        return response()->json([
            'success' => true,
            'message' => 'List data city',
            'data' => $city            
        ]);
    }

    public function checkOngkir(Request $request)
    {
        
        $cost = RajaOngkir::ongkosKirim([
            'origin' => 154,
            'destination' => $request->city_destination,
            'weight' => $request->weight,
            'courier' => $request->courier
        ])->get();


        return response()->json([
            'success' => true,
            'message' => 'List data cost courier by :' .$request->courier,
            'data' => $cost
        ]);
    }
}
