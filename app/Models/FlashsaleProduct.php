<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashsaleProduct extends Model
{
    use HasFactory;

    protected $table = 'flashsale_deal_products';

    protected $guarded = [];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function flashsale()
    {
        return $this->belongsTo(Flashsale::class);
    }
}
