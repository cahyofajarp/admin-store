<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//---------------------------------------------------------------------------------------------------
//AUTH USER CLIENT

Route::post('/login-user', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/logout-user', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

//---------------------------------------------------------------------------------------------------


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth:sanctum')->get('/dashboard-data', function() {
    echo 'you are loggin';
});


Route::group(['prefix' => 'admin'],function() {
    Route::get('/dashboard',[App\Http\Controllers\Admin\DashboardController::class,'index'])->name('admin.dashboard');
    
    //---------------------------------------------------
    Route::get('/sliders',[App\Http\Controllers\Admin\SliderController::class,'index'])->name('admin.sliders');

    Route::post('/sliders/store',[App\Http\Controllers\Admin\SliderController::class,'store'])->name('admin.sliders.store');
    Route::post('/sliders/test',[App\Http\Controllers\Admin\SliderController::class,'test'])->name('admin.sliders.test');
    
    Route::get('/slider/edit/{slider}',[App\Http\Controllers\Admin\SliderController::class,'edit'])->name('admin.sliders.edit');
    
    Route::post('/slider/update/{slider}',[App\Http\Controllers\Admin\SliderController::class,'update'])->name('admin.sliders.update');
    
    Route::delete('/slider/destroy/{slider}',[App\Http\Controllers\Admin\SliderController::class,'destroy'])->name('admin.sliders.destroy');
    
    //------------------------------------------------------
    Route::get('/category',[App\Http\Controllers\Admin\CategoryController::class,'index'])->name('admin.category');
    
    Route::post('/category/store',[App\Http\Controllers\Admin\CategoryController::class,'store'])->name('admin.category.store');
    
    Route::get('/category/edit/{category}',[App\Http\Controllers\Admin\CategoryController::class,'edit'])->name('admin.category.edit');
    
    Route::post('/category/update/{category}',[App\Http\Controllers\Admin\CategoryController::class,'update'])->name('admin.category.update');
    
    Route::delete('/category/destroy/{category}',[App\Http\Controllers\Admin\CategoryController::class,'destroy'])->name('admin.category.destroy');
     
    //---------------------------------------------------------------------------

    Route::resource('sizecolor',App\Http\Controllers\Admin\SizeColorController::class);
    Route::resource('size',App\Http\Controllers\Admin\SizeController::class);

    Route::resource('hightlight',App\Http\Controllers\Admin\HightlightProductController::class);
    
    //----------------------------------------------------------------------------
    Route::get('/product',[App\Http\Controllers\Admin\ProductController::class,'index'])->name('admin.product');
   
    Route::get('/product/{product}/add-stock',[App\Http\Controllers\Admin\ProductController::class,'createStock'])->name('admin.product.createStock');
    
    Route::post('/product/{product}/add-stock',[App\Http\Controllers\Admin\ProductController::class,'storeStock'])->name('admin.product.storeStock');
     
    Route::get('/product/{product}/{stock}edit-stock',[App\Http\Controllers\Admin\ProductController::class,'editStock'])->name('admin.product.editStock');
    
    Route::post('/product/{product}/{stock}/update-stock',[App\Http\Controllers\Admin\ProductController::class,'updateStock'])->name('admin.product.updateStock');
    
    Route::delete('/product/{product}/{stock}/delete',[App\Http\Controllers\Admin\ProductController::class,'deleteStock'])->name('admin.product.deleteStock');
    
    Route::post('/product/store',[App\Http\Controllers\Admin\ProductController::class,'store'])->name('admin.product.store');
    
    Route::get('/product/edit/{product}',[App\Http\Controllers\Admin\ProductController::class,'edit'])->name('admin.product.edit');
    
    Route::post('/product/update/{product}',[App\Http\Controllers\Admin\ProductController::class,'update'])->name('admin.product.update');
    
    Route::delete('/product/destroy/{product}',[App\Http\Controllers\Admin\ProductController::class,'destroy'])->name('admin.product.destroy');
    
    Route::get('/product/{product}/preview',[App\Http\Controllers\Admin\ProductController::class,'show'])->name('admin.product.preview');
    
  
    // --------------------------------------------

    Route::get('/gallery',[App\Http\Controllers\Admin\ProductGalleryController::class,'index'])->name('admin.gallery');
    
    Route::get('/gallery/{product}/color',[App\Http\Controllers\Admin\ProductGalleryController::class,'color'])->name('admin.gallery.color');
    
    Route::post('/gallery/{product}/color',[App\Http\Controllers\Admin\ProductGalleryController::class,'colorKeyGallery'])->name('admin.gallery.colorKeyGallery');
   
    Route::get('/gallery/{product}/{color}/show',[App\Http\Controllers\Admin\ProductGalleryController::class,'show'])->name('admin.gallery.show');
    
    Route::post('/gallery/{product}/{color}/show',[App\Http\Controllers\Admin\ProductGalleryController::class,'createThumbnail'])->name('admin.gallery.createThumbnail');
    
    Route::get('/gallery/{product}/{color}/create',[App\Http\Controllers\Admin\ProductGalleryController::class,'create'])->name('admin.gallery.create');
    
    Route::post('/gallery/{product}/{color}/store',[App\Http\Controllers\Admin\ProductGalleryController::class,'store'])->name('admin.gallery.store');
    
    Route::get('/gallery/edit/{gallery}',[App\Http\Controllers\Admin\ProductGalleryController::class,'edit'])->name('admin.gallery.edit');
    
    Route::post('/gallery/update/{gallery}',[App\Http\Controllers\Admin\ProductGalleryController::class,'update'])->name('admin.gallery.update');
    
    Route::delete('/gallery/destroy/{gallery}',[App\Http\Controllers\Admin\ProductGalleryController::class,'destroy'])->name('admin.gallery.destroy');
    
    Route::get('/gallery/{gallery}/preview',[App\Http\Controllers\Admin\ProductGalleryController::class,'show'])->name('admin.gallery.preview');

    //------------------------------------
    Route::get('/flashsale',[App\Http\Controllers\Admin\FlashsaleController::class,'index'])->name('admin.flashsale');
    
    Route::get('/flashsale/create',[App\Http\Controllers\Admin\FlashsaleController::class,'create'])->name('admin.flashsale.create');
    
    Route::get('/flashsale/{flashsale}/product',[App\Http\Controllers\Admin\FlashsaleController::class,'product'])->name('admin.flashsale.product');
    
    Route::post('/flashsale/store',[App\Http\Controllers\Admin\FlashsaleController::class,'store'])->name('admin.flashsale.store');
    
    Route::post('/flashsale/{flashsale}/search/product',[App\Http\Controllers\Admin\FlashsaleController::class,'search'])->name('admin.flashsale.search.product');
   
    Route::post('/flashsale/search/product',[App\Http\Controllers\Admin\FlashsaleController::class,'searchCreate'])->name('admin.flashsale.searchCreate.product');
    
    Route::get('/flashsale/edit/{flashsale}',[App\Http\Controllers\Admin\FlashsaleController::class,'edit'])->name('admin.flashsale.edit');
    
    Route::put('/flashsale/update/{flashsale}',[App\Http\Controllers\Admin\FlashsaleController::class,'update'])->name('admin.flashsale.update');
    
    Route::delete('/flashsale/destroy/{flashsale}',[App\Http\Controllers\Admin\FlashsaleController::class,'destroy'])->name('admin.flashsale.destroy');
    
    Route::get('/flashsale/{flashsale}/preview',[App\Http\Controllers\Admin\FlashsaleController::class,'show'])->name('admin.flashsale.preview');
    
    //--------------------------------------------------------------------------------------------------

    Route::get('/order/management',[App\Http\Controllers\Admin\OrderController::class,'index'])->name('admin.order');
    
}); 