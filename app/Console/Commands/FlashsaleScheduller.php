<?php

namespace App\Console\Commands;

use App\Models\Flashsale;
use App\Models\FlashsaleProduct;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FlashsaleScheduller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashsale:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flashsale change status and delete product from flashsale';

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

        $flashsale = Flashsale::latest()->first();
            
        if($flashsale){
            $flashsale_deal_product = FlashsaleProduct::with(['product'])->where('flashsale_id',$flashsale->id);
           
             if($now < $flashsale->end_flashsale && $now > $flashsale->start_flashsale){
                
                foreach($flashsale_deal_product->get() as $productData){
                    $productData->product->update([
                        'price_after_flashsale' =>  $productData->product->price - ($productData->discount / 100) * $productData->product->price
                    ]);
                }
                
                $flashsale->update([
                    'status' => 'ongoing',
                ]);
            }
            else if($now < $flashsale->start_flashsale){
                $flashsale->update([
                    'status' => 'pending',
                ]);

            }
        }

    }
}
