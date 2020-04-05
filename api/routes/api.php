<?php

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

Route::get('/currencies', ['as' => 'currencies.list', 'uses' => 'CurrencyController@index']);

Route::prefix('accounts')->group(function () {
    Route::get('/', ['as' => 'accounts.list', 'uses' => 'AccountController@index']);
    Route::post('/', ['as' => 'accounts.store', 'uses' => 'AccountController@store']);
    Route::get('/{id}', ['as' => 'accounts.show', 'uses' => 'AccountController@show']);
    Route::put('/{id}', ['as' => 'accounts.update', 'uses' => 'AccountController@update']);
    Route::delete('/{id}', ['as' => 'accounts.destroy', 'uses' => 'AccountController@destroy']);

    Route::get('/{id}/transactions', ['as' => 'account-transactions.list', 'uses' => 'TransactionController@getAllTransactionsByAccount']);
    Route::get('/{id}/transactions-made', ['as' => 'account-transactions-made.list', 'uses' => 'TransactionController@getMadeTransactionsByAccount']);
    Route::get('/{id}/transactions-received', ['as' => 'account-transactions-received.list', 'uses' => 'TransactionController@getReceivedTransactionsByAccount']);
});

Route::prefix('transactions')->group(function () {
    Route::get('/', ['as' => 'transactions.list', 'uses' => 'TransactionController@index']);
    Route::post('/', ['as' => 'transactions.store', 'uses' => 'TransactionController@store']);
    Route::get('/{id}', ['as' => 'transactions.show', 'uses' => 'TransactionController@show']);
});
