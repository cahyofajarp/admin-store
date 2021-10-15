<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productgallery_thumbnail extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the color that owns the productgallery_thumbnail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function product_gallery()
    {
        return $this->hasOne(ProductGalleries::class,'id','product_galleries_id');
    }

    
}
