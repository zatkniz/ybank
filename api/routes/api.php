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

Route::get('accounts/{account}', 'AccountController@getAccount');
Route::get('accounts/{id}/transactions', 'TransactionController@getAccountTransactionHistory');
Route::post('transactions', 'TransactionController@save');
Route::get('currencies', 'CurrencyController@all');
