<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flashsale extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the flashsaleDeal that owns the Flashsale
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function flashsale_deal()
    {
        return $this->hasMany(FlashsaleProduct::class);
    }
}
