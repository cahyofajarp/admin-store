<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGalleries extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getImageAttribute($image)
    {
        return asset('storage/' . $image);
    }
    /**
     * Get the product that owns the ProductGalleries
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the color that owns the ProductGalleries
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function color()
    {
        return $this->belongsTo(Color::class);
    } 
    
    /**
    * Get the color that owns the ProductGalleries
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function productgallery_thumbnails()
   {
       return $this->hasMany(productgallery_thumbnail::class);
   }
}
