<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function productgallery_thumbnails()
    {
        return $this->hasOne(productgallery_thumbnail::class)->latest();
    }

    public function first_thumbnail() {
        return $this->hasOne(ProductGalleries::class);
    }
 }
