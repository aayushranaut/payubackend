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
    Route::get('stock/buy/{username}/{stockname}/{quantity}', 'StocksController@buy');
    Route::get('stock/sell/{username}/{trade_id}', 'StocksController@sell');
    Route::get('stock/{stock_id}', 'StocksController@show');

    //Portfolio
    Route::get('portfolio/{username}', 'PortfolioController@show');

    //Leaderboards
    Route::get('leaderboard/net', 'LeaderboardController@net');
    Route::get('leaderboard/profit', 'LeaderboardController@profit');

    Route::get('cron/manipulate', 'CronController@manipulate');
});