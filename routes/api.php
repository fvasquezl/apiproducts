<?php

use Illuminate\Http\Request;

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


/**
 * buyers
 */
Route::apiResource('buyers','Buyer\BuyerController')->only(['index','show']);
Route::apiResource('buyers.sellers','Buyer\BuyerSellerController')->only(['index']);
Route::apiResource('buyers.products','Buyer\BuyerProductController')->only(['index']);
Route::apiResource('buyers.categories','Buyer\BuyerCategoryController')->only(['index']);
Route::apiResource('buyers.transactions','Buyer\BuyerTransactionController')->only(['index']);
/**
 * categories
 */
Route::apiResource('categories','Category\CategoryController');
Route::apiResource('categories.buyers','Category\CategoryBuyerController')->only(['index']);
Route::apiResource('categories.sellers','Category\CategorySellerController')->only(['index']);
Route::apiResource('categories.products','Category\CategoryProductController')->only(['index']);
Route::apiResource('categories.transactions','Category\CategoryTransactionController')->only(['index']);
/**
 * products
 */
Route::apiResource('products','Product\ProductController')->only('index','show');
Route::apiResource('products.transactions','Product\ProductTransactionController')->only('index');
Route::apiResource('products.buyers','Product\ProductBuyerController')->only('index');
Route::apiResource('products.categories','Product\ProductCategoryController')->only(['index','update','destroy']);
Route::apiResource('products.buyer.transactions','Product\ProductBuyerTransactionController')->only(['store']);
/**
 * transactions
 */
Route::apiResource('transactions','Transaction\TransactionController')->only('index','show');
Route::apiResource('transactions.sellers','Transaction\TransactionSellerController')->only('index');
Route::apiResource('transactions.categories','Transaction\TransactionCategoryController')->only('index');

/**
 * sellers
 */
Route::apiResource('sellers','Seller\SellerController')->only('index','show');
Route::apiResource('sellers.buyers','Seller\SellerBuyerController')->only('index');
Route::apiResource('sellers.products','Seller\SellerProductController')->except(['create','show','edit']);
Route::apiResource('sellers.categories','Seller\SellerCategoryController')->only('index');
Route::apiResource('sellers.transactions','Seller\SellerTransactionController')->only('index');
/**
 * users
 */
Route::apiResource('users','User\UserController');
Route::name('verify')->get('users/verify/{token}','User\UserController@verify');
Route::name('resend')->get('users/{user}/resend','User\UserController@resend');