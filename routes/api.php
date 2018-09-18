<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

Route::get('/accountsHead',
	[
		'as' => 'accountsHeadDetails',
		'uses' => 'API\AccountHeadApi@getAccountsHead',
	]);

Route::get('/accountsSubHead/{account_head_id?}',
	[
		'as' => 'accountsHeadDetails',
		'uses' => 'API\AccountHeadApi@getAccountsSubHead',
	]);

Route::get('/accHeadClass/{account_sub_head_id?}',
	[
		'as' => 'accountsHeadDetails',
		'uses' => 'API\AccountHeadApi@getAccClass',
	]);

Route::get('/accSubHeadClass/{acc_class_id?}',
	[
		'as' => 'accountsHeadDetails',
		'uses' => 'API\AccountHeadApi@getAccSubClass',
	]);

Route::get('/getProduct/{id?}',
	[
		'as' => 'accountsHeadDetails',
		'uses' => 'API\GeneralData@getProductById',
	]);

Route::get('/accSubHeadClassReverse/{acc_sub_class_id?}',
	[
		'as' => 'accountsHeadDetails',
		'uses' => 'API\AccountHeadApi@getAccSubClassReverse',
	]);