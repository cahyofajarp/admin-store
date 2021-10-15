<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function color()
    {
        return $this->belongsTo(Color::class);
    }   
    
    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function product_gallery()
    {
        return $this->hasOne(ProductGalleries::class,'id','product_galleries_id');
    }
}
