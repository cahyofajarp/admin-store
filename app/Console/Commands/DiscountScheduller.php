<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\Flashsale;
use App\Models\Product;
use Illuminate\Console\Command;

class DiscountScheduller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discount:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Discount Scheduller.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   

        $now = \Carbon\Carbon::now();
        
        $productDiscount = Product::with(['carts' => function($q) {
            $q->groupBy('product_id');
        }])->has('carts')
        ->where('end_of_discount','<',$now)
        ->where('discount','<>',0)->get();
        
        foreach($productDiscount as $product){
            if($now > $product->end_of_discount){
                
                $product->update([
                    'discount' => 0,
                    'end_of_discount' => null,
                    'price_after_discount' => null,
                ]);

                $data =  [
                    'id' => $product->carts->pluck('product_id'),
                    'price' => $product->price
                ];
                
                $update = Cart::whereIn('product_id',$data['id'])->update([
                    'price' => $data['price']
                ]);
                
            }
        }
    }
}
