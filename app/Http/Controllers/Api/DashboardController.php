<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $customer  = Customer::where('id',auth()->guard('api')->user()->id)->first();

        if($customer){
            return response()->json([
                'success' => true,
                'message' => 'Data Customer'. $customer->name,
                'user'    => $customer
            ]);
        }
    }

    public function changeImage(Request $request)
    {
        $this->validate($request,[
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
        ]);

        $customer = Customer::where('id',auth()->guard('api')->user()->id)->first();
        
        if($customer){
            $image = $customer->image;
            
            if($request->hasFile('image')){
                
                if($customer->image){  
                    Storage::disk('public')->delete('profile/images/'.basename($customer->image));
                }
                $image = $request->file('image')->store('profile/images','public');
            }

            $customer->update([
                'image' => $image,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Successfully updated profile',
                'data' => basename($customer->image)
            ],200);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Unautenhicated.'
            ],401);
        }
    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email|unique:customers,email,'.auth()->guard('api')->user()->id,
        ]);

        $customer = Customer::where('id',auth()->guard('api')->user()->id)->first();

        if($customer){
            $customer->update([
                'email' => $request->email,
                'name'  => $request->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Success updated profile for '.$customer->name,
                'user'    => $customer
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Failed updated profile'
            ]);
        }
    }
}
