<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
    public function flashsaleProduct()
    {
        return $this->hasOne(FlashsaleProduct::class);
    }
    
    public function productGalleries()
    {
        return $this->hasMany(ProductGalleries::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
    
    public function first_thumbnail() {
        return $this->hasOne(ProductGalleries::class)->latest();
    } 

    public function colorGallery() {
        return $this->hasOne(ProductGalleries::class)->latest()->groupBy('product_id');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class)->withTimestamps();
    }  
    
    public function color()
    {
        return $this->hasOne(Color::class);
    }
    
    public function sizes()
    {
        return $this->belongsToMany(Size::class)->withTimestamps();
    }

     public function productgallery_thumbnails()
   {
       return $this->hasOne(productgallery_thumbnail::class);
   }
   
   public function flashsale_products()
   {
       return $this->hasMany(FlashsaleProduct::class);
   }
   public function carts()
   {
       return $this->hasMany(Cart::class);
   }
}
