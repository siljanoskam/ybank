<?php

use Illuminate\Http\Request;
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

Route::get('/currencies', ['as' => 'currencies.list', 'uses' => 'CurrenciesController@index']);

Route::prefix('accounts')->group(function () {
    Route::get('/', ['as' => 'accounts.list', 'uses' => 'AccountController@index']);
    Route::post('/', ['as' => 'accounts.store', 'uses' => 'AccountController@store']);
    Route::get('/{id}', ['as' => 'accounts.show', 'uses' => 'AccountController@show']);
    Route::put('/{id}', ['as' => 'accounts.update', 'uses' => 'AccountController@update']);
    Route::delete('/{id}', ['as' => 'accounts.destroy', 'uses' => 'AccountController@destroy']);

    Route::get('/{id}/transactions', ['as' => 'account.transactions', 'uses' => 'AccountController@getTransactions']);
    Route::post('/{id}/transactions', ['as' => 'account.transactions', 'uses' => 'AccountController@makeTransaction']);
});
