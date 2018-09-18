<?php

Route::group(['middleware' => 'auth'], function () {

	Route::group(['middleware' => 'routeAccess'], function () {

		Route::group(['prefix' => 'product'], function () {
			// Sale
			Route::get('sale',
				[
					'as' => 'product_sale_view',
					'uses' => 'sale\SaleManagement@saleTableForm',
				]);
			Route::get('sale/list/{p?}',
				[
					'as' => 'product_sale_list_view',
					'uses' => 'sale\SaleManagement@saleList',
				]);
			Route::post('sale/add',
				[
					'as' => 'product_sale_add_action',
					'uses' => 'sale\SaleManagement@saleTableAdd',
				]);
			Route::post('sale/list/search',
				[
					'as' => 'product_sale_list_search',
					'uses' => 'sale\SaleManagement@saleSearchList',
				]);
			Route::any('get_Order_No',
				[
					'uses' => 'API\GeneralData@getOrderNo',
				]);
			Route::post('/getFromValue',
				[
					'as' => 'product_sale_form',
					'uses' => 'sale\SaleManagement@frm_value',
				]);
			// End of Sale

			Route::any('get_product_group_sale',
				[
					'as' => 'get_product_group_sale',
					'uses' => 'sale\SaleManagement@getProductGroup',
				]);
			Route::any('get_product_group_sale_lc',
				[
					'as' => 'get_product_group_sale_lc',
					'uses' => 'sale\SaleManagement@getProductGroupLc',
				]);

			Route::any('get_last_sale_id',
				[
					'as' => 'get_last_sale_id',
					'uses' => 'sale\SaleManagement@getLastSaleId',
				]);

			Route::any('check_amount',
				[
					'as' => 'check_amount',
					'uses' => 'sale\SaleManagement@checkAmount',
				]);

			Route::any('inventory/report',
				[
					'as' => 'inventory_report_view',
					'uses' => 'sale\SaleManagement@inventoryReport_view',
				]);

			//start Lc purchase
			Route::any('lc_purchase/table',
				[
					'as' => 'lc_purchase_view',
					'uses' => 'product\LCPurchaseManagement@lcPurchaseTable',
				]);
			Route::any('lc_purchase/data/insert',
				[
					'as' => 'lc_purchase_table_action',
					'uses' => 'product\LCPurchaseManagement@lcPurchaseTableInsert',
				]);
			//end LC purchase
			//start Local purchase
					Route::post('/get_from_value',
						[
							'as' => 'get_from_value',
							'uses' => 'product\PurchaseManagement@purchaseTableVal',
						]);

			// End of Local Purchase
			//sales return
Route::group(['prefix' => 'sale'], function () {

			Route::get('/sales_return',
				[
					'as' => 'sales_return',
					'uses' => 'sale\SalereturnManagement@SalereturnView',
				]);

			Route::post('sale_return',
				[
					'as' => 'sales_return_get',
					'uses' => 'sale\SalereturnManagement@getSalesResult',
				]);
			Route::post('return_store',
				[
					'as' => 'sales_return_store',
					'uses' => 'sale\SalereturnManagement@storeSaleReturn',
				]);
		});

		});

	});

	// Route::any('pro_deduction', function () {
	// 	return "1234";
	// });

	Route::any('pro_deduction',
		[
			'as' => 'pro_deduction',
			'uses' => 'sale\SaleManagement@proDeduction',
		]);

});

Route::group(['middleware' => 'auth'], function () {

	Route::group(['middleware' => 'routeAccess'], function () {

		Route::group(['prefix' => 'journal'], function () {

			Route::any('posting',
				[
					'as' => 'journal_posting_view',
					'uses' => 'journal\JournalController@listView',
				]);
			Route::any('posting/form',
				[
					'as' => 'journal_posting_form_view',
					'uses' => 'journal\JournalController@formView',
				]);
			Route::any('posting/form/upload/',
				[
					'as' => 'journal_posting_form_action',
					'uses' => 'journal\JournalController@dataUpload',
				]);

		});

		Route::group(['prefix' => 'trialbalance'], function () 
		{
			Route::get('/index',
				[
					'as' => 'trail_balance_view',
					'uses' => 'trailbalance\TrailbalanceController@listView',
				]);
		});

	});

	Route::any('get_user_by_com_id/{comId?}',
		[
			'uses' => 'API\GeneralData@getCompanyUser',
		]);

	Route::any('get_last_voucher_id',
		[
			'uses' => 'API\GeneralData@getLastVoucherId',
		]);

	Route::any('/get_trail_balances',
		[
			'as' => 'get_trail_balances',
			'uses' => 'trailbalance\TrailbalanceController@trialBalanceList',
		]);



	// added later for add journal


    Route::any('get_role_name/{companyId?}',
        [
            'uses' => 'API\GeneralData@getRoleName',
        ]);

});


Route::group(['middleware' => 'auth'], function () {

	Route::group(['middleware' => 'routeAccess'], function () {

		Route::group(['prefix' => 'chalan'], function () {

			Route::any('/chalan_view',
				[
					'as' => 'chalan_view',
					'uses' => 'chalan\chalanController@chalanView',
				]);
			Route::post('/generate',
				[
					'as' => 'chalan_generate_action',
					'uses' => 'chalan\chalanController@generteChalan',
				]);
		});
	});
});
