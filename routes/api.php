<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Auth::routes(['verify' => true]);

Route::post('/login',[AuthController::class,'login'])->name('api.customer.login');

Route::post('/logout',[AuthController::class,'logout'])->name('api.customer.logout');

Route::post('email/verification-notification', [VerificationController::class, 'resend'])->name('verification.resend');

Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

Route::post('/refresh',[AuthController::class,'refresh'])->name('api.customer.refresh');

Route::post('/checkToken',[AuthController::class,'checkToken'])->name('api.customer.checkToken');

Route::post('/register',[AuthController::class,'register'])->name('api.customer.register');

Route::get('/user',[AuthController::class,'getUser'])->name('api.customer.user');

Route::get('/checkVerified',[AuthController::class,'checkVerified'])->name('api.customer.user');

Route::post('/customer/profile/image/update',[DashboardController::class,'changeImage'])->name('api.customer.changeImage');

Route::get('/customer/info',[DashboardController::class,'index'])->name('api.customer.index');

Route::put('/customer/update',[DashboardController::class,'update'])->name('api.customer.update');
//---------------------------------------------------------------------------------------------

Route::get('/orders',[OrderController::class,'index'])->name('api.order.index');

Route::get('/order/{snap_token}',[OrderController::class,'show'])->name('api.order.show');

Route::get('/order-search',[OrderController::class,'orderSearch'])->name('api.order.orderSearch');

//-------------------------------------------------------------------------------------------

Route::get('/category',[CategoryController::class,'index'])->name('api.category.index');

Route::get('/categoryHome',[CategoryController::class,'categoryHome'])->name('api.category.categoryHome');

Route::get('/category/{slug?}',[CategoryController::class,'show'])->name('api.category.show');

//-------------------------------------------------------------------------------------------------

Route::get('/productHome',[ProductController::class,'productHome'])->name('api.product.productHome');

Route::get('/product',[ProductController::class,'index'])->name('api.product.index');

Route::post('/product/filterColor',[ProductController::class,'filterColor'])->name('api.product.filterColor');

Route::get('/product/flashsale',[FlashsaleController::class,'index'])->name('api.flashsale.index');

Route::post('/product/flashsale/finish',[FlashsaleController::class,'cartDeleteFlashsale'])->name('api.flashsale.cartDeleteFlashsale');
    
Route::get('/product/{slug?}',[ProductController::class,'show'])->name('api.product.show');


Route::get('/product/{slug?}/{color_id?}/{size_id}/change-color',[ProductController::class,'changeColor'])->name('api.product.changeColor');

// highlight ------------------------------------------
Route::get('/productHome/hightlight',[HightlightProductController::class,'index'])->name('api.producthome.highlight');

//---------------------------------------------------------------------------------------------------

Route::post('/productSearch',[ProductController::class,'productSearch']);
//-------------------------------------------------------------------------------------------------

Route::get('/product-wishlist/wishlist',[WishlishController::class,'index'])->name('api.wishlist.index');

Route::post('/product-wishlist/wishlist/store',[WishlishController::class,'store'])->name('api.wishlist.store');

Route::post('/product-wishlist/wishlist/search',[WishlishController::class,'searchWishlist'])->name('api.wishlist.search');

Route::delete('/product-wishlist/wishlist/delete',[WishlishController::class,'delete'])->name('api.wishlist.delete');
//--------------------------------------------------------------------------------------------------
// -----------------------------------------
// Cart Route

Route::get('/cart',[CartController::class,'index'])->name('api.cart.index');

Route::post('/cart',[CartController::class,'store'])->name('api.cart.store');

Route::get('/cart/price',[CartController::class,'getCartTotal'])->name('api.cart.price');

Route::get('/cart/weight',[CartController::class,'getCartTotalWeight'])->name('api.cart.weight');

Route::get('/cart/cartCount',[CartController::class,'getCartCount'])->name('api.cart.cartCount');

Route::delete('/cart/remove',[CartController::class,'removeCart'])->name('api.cart.remove');

Route::post('/cart/updateQty',[CartController::class,'updateCart'])->name('api.cart.updateCart');

Route::post('/cart/removeAll',[CartController::class,'removeAllCart'])->name('api.cart.removeAll');

Route::get('/rajaongkir/province',[RajaOngkirController::class,'getProvince'])->name('api.rajaongkir.province');

Route::get('/rajaongkir/cities',[RajaOngkirController::class,'getCities'])->name('api.rajaongkir.city');

Route::post('/rajaongkir/check-ongkir',[RajaOngkirController::class,'checkOngkir'])->name('api.rajaongkir.checkOngkir');

Route::post('/checkout',[CheckoutController::class,'store'])->name('api.checkout.checkout');
Route::post('/notification-handler',[CheckoutController::class,'notificationHandler'])->name('api.checkout.notificationHandler');

Route::get('/sliders',[SliderController::class,'index'])->name('api.slider.index');
