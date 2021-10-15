<?php

namespace App\Console\Commands;

use App\Models\HightlightProduct;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class HightlightScheduller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'highlight:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For handle highlight schedule';

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
        $hightlight = HightlightProduct::with(['product'])->latest()->first();
        $now = \Carbon\Carbon::now();
            
        if($hightlight){
            if($hightlight->product->end_of_discount != null && $now > $hightlight->product->end_of_discount){
                if($hightlight->image){
                    Storage::disk('local')->delete('public/hightlight/'.basename($hightlight->image));
                    // Storage::disk('local')->delete('public/sliders/'.basename($slider->image));
                }
                
                $hightlight->delete();
            }
        }
    }
}
