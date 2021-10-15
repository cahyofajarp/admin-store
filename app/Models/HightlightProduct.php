<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HightlightProduct extends Model
{
    use HasFactory;
    
    protected $table = 'hightlight_products';

    protected $guarded = [];

    public function getImageAttribute($image)
    {
        return asset('storage/'.$image);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
