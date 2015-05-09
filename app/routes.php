<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(['prefix' => 'api/v1'], function(){
    //Users
    Route::get('user/{username}', 'UsersController@show');

    //Stocks
    Route::get('stock/trending', 'StocksController@trending');
    Route::get('stock/{stock_id}', 'StocksController@show');

    //Portfolio
    Route::get('portfolio/{username}', 'PortfolioController@show');
});