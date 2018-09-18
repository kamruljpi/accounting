<?php

Route::group(['middleware' => 'auth'], function () {

	Route::post('/searchGeneralLedger',
		[
			'as' => 'ledger_general_search_action',
			'uses' => 'ledger\GeneralLedgerManagement@SearchAction',
		]);

	Route::post('/searchPartyLedger',
		[
			'as' => 'ledger_party_search_action',
			'uses' => 'ledger\PartyLedgerManegement@SearchAction',
		]);

	Route::get('/detailsPartyJournalOfLedger/',
		[
			'as' => 'detailsJournalOfLedger_action',
			'uses' => 'ledger\PartyLedgerManegement@getPartyJournalDetails',
		]);

	Route::get('/detailsGeneralJournalOfLedger/',
		[
			'as' => 'detailsJournalOfLedger_action',
			'uses' => 'ledger\GeneralLedgerManagement@getGeneralJournalDetails',
		]);

	Route::group(['middleware' => 'routeAccess'], function () {

		Route::group(['prefix' => 'lc_product'], function () {
			// L/C Purchase
			Route::get('lc_purchase_list',
				[
					'as' => 'lc_purchase_list_view',
					'uses' => 'product\LCPurchaseManagement@lcPurchaseListView',
				]);
			Route::post('lc_purchase_list',
				[
					'as' => 'lc_product_purchase_search_list',
					'uses' => 'product\LCPurchaseManagement@lcPurchaseSearchList',
				]);
			Route::any('purchase/summary/{lc_purchase_product_id?}',
				[
					'as' => 'lc_product_purchase_summary',
					'uses' => 'product\LCPurchaseManagement@lcPurchaseSummaryView',
				]);
			Route::any('purchase/table_update/{lc_purchase_product_id?}',
				[
					'as' => 'lc_product_purchase_table_update',
					'uses' => 'product\LCPurchaseManagement@lcPurchaseTableUpdateView',
				]);
			Route::post('purchase/lc_purchase_update_post',
				[
					'as' => 'lc_product_purchase_update_action',
					'uses' => 'product\LCPurchaseManagement@lcPurchaseUpdateAction',
				]);
			Route::post('purchase/',
				[
					'as' => '',
					'uses' => 'product\LCPurchaseManagement@lcPurchaseSummaryView',
				]);
			//for lc export
			Route::get('items/export/from/{dateSearchFldFrom}/to/{dateSearchFldTo}/type/{type}',
				[
					'as' => 'lc_p_export_action',
					'uses' => 'product\LCPurchaseManagement@excel',
				]);

		});

		// accounts

		Route::group(['prefix' => 'accounts_head'], function () {

			Route::get('account_head_index',
				[
					'as' => 'accounts_head_name_view',
					'uses' => 'Accounts_Head\AccountsHeadName@account_head_index',
				]);

			Route::get('/add_account_head',
				[
					'as' => 'add_accounts_head_view',
					'uses' => 'Accounts_Head\AccountsHeadName@addAccountHeadForm',
				]);

			Route::post('/add_account_head',
				[
					'as' => 'add_account_head_action',
					'uses' => 'Accounts_Head\AccountsHeadName@addAccountHeadAction',
				]);

			Route::get('/edit_account_head/{id?}',
				[
					'as' => 'edit_accounts_head_view',
					'uses' => 'Accounts_Head\AccountsHeadName@editAccountHeadForm',
				]);

			Route::post('/edit_account_head',
				[
					'as' => 'edit_account_head_action',
					'uses' => 'Accounts_Head\AccountsHeadName@editAccountHeadAction',
				]);

			Route::get('/delete_account_head/{id?}',
				[
					'as' => 'delete_accounts_head_action',
					'uses' => 'Accounts_Head\AccountsHeadName@deleteAccountHead',
				]);


			Route::get('account_sub_head_index/{p?}',
				[
					'as' => 'accounts_sub_head_name_view',
					'uses' => 'Accounts_Head\AccountsSubHeadName@sub_account_head_index',
				]);

			Route::get('/add_sub_account_head',
				[
					'as' => 'add_sub_accounts_head_view',
					'uses' => 'Accounts_Head\AccountsSubHeadName@addSubAccountHeadForm',
				]);

			Route::post('/add_sub_account_head',
				[
					'as' => 'add_sub_account_head_action',
					'uses' => 'Accounts_Head\AccountsSubHeadName@addSubAccountHeadAction',
				]);

			Route::get('/edit_sub_account_head/{id?}',
				[
					'as' => 'edit_sub_accounts_head_view',
					'uses' => 'Accounts_Head\AccountsSubHeadName@editSubAccountHeadForm',
				]);

			Route::post('/edit_sub_account_head',
				[
					'as' => 'edit_sub_account_head_action',
					'uses' => 'Accounts_Head\AccountsSubHeadName@editSubAccountHeadAction',
				]);

			Route::get('/delete_sub_account_head/{id?}',
				[
					'as' => 'delete_sub_accounts_head_action',
					'uses' => 'Accounts_Head\AccountsSubHeadName@deleteSubAccountHead',
				]);
			});
			// acc_class ################################################

		Route::group(['prefix' => 'acc_class'], function(){
			Route::get('index/{p?}',
				[
					'as' => 'acc_class_index',
					'uses' => 'Accounts_Head\AccClassManagement@index',
				]);
			Route::get('create',
				[
					'as' => 'acc_class_create_view',
					'uses' => 'Accounts_Head\AccClassManagement@createView',
				]);
			Route::post('create',
				[
					'as' => 'acc_class_create_action',
					'uses' => 'Accounts_Head\AccClassManagement@createAction',
				]);
			Route::get('update/{id?}',
				[
					'as' => 'acc_class_update_view',
					'uses' => 'Accounts_Head\AccClassManagement@updateView',
				]);
			Route::post('update',
				[
					'as' => 'acc_class_update_action',
					'uses' => 'Accounts_Head\AccClassManagement@updateAction',
				]);
			Route::get('delete/{id?}',
				[
					'as' => 'acc_class_delete_action',
					'uses' => 'Accounts_Head\AccClassManagement@deleteAction',
				]);
			});

			// acc_sub_class ################################################
			
			Route::group(['prefix' => 'acc_sub_class'], function(){
				Route::get('index/{p?}',
					[
						'as' => 'acc_sub_class_index',
						'uses' => 'Accounts_Head\AccSubClassManagement@index',
					]);
				Route::get('create',
					[
						'as' => 'acc_sub_class_create_view',
						'uses' => 'Accounts_Head\AccSubClassManagement@createView',
					]);
				Route::post('create',
					[
						'as' => 'acc_sub_class_create_action',
						'uses' => 'Accounts_Head\AccSubClassManagement@createAction',
					]);
				Route::get('update/{id?}',
					[
						'as' => 'acc_sub_class_update_view',
						'uses' => 'Accounts_Head\AccSubClassManagement@updateView',
					]);
				Route::post('update',
					[
						'as' => 'acc_sub_class_update_action',
						'uses' => 'Accounts_Head\AccSubClassManagement@updateAction',
					]);
				Route::get('delete/{id?}',
					[
						'as' => 'acc_sub_class_delete_action',
						'uses' => 'Accounts_Head\AccSubClassManagement@deleteAction',
					]);
			});

		// chart_of_account ################################################
			
		Route::group(['prefix' => 'chart_of_acc'], function(){
			Route::get('index/{p?}',
				[
					'as' => 'chart_of_acc_index',
					'uses' => 'Accounts_Head\ChartOfAccManagement@index',
				]);
			Route::get('create',
				[
					'as' => 'chart_of_acc_create_view',
					'uses' => 'Accounts_Head\ChartOfAccManagement@createView',
				]);
			Route::post('create',
				[
					'as' => 'chart_of_acc_create_action',
					'uses' => 'Accounts_Head\ChartOfAccManagement@createAction',
				]);
			Route::get('update/{id?}',
				[
					'as' => 'chart_of_acc_update_view',
					'uses' => 'Accounts_Head\ChartOfAccManagement@updateView',
				]);
			Route::post('update',
				[
					'as' => 'chart_of_acc_update_action',
					'uses' => 'Accounts_Head\ChartOfAccManagement@updateAction',
				]);
			Route::get('delete/{id?}',
				[
					'as' => 'chart_of_acc_delete_action',
					'uses' => 'Accounts_Head\ChartOfAccManagement@deleteAction',
				]);
			});

		// Accounting ledger ################################################
	
		Route::group(['prefix' => 'ledger'], function(){
			Route::group(['prefix' => 'party'], function(){
				Route::get('index/{p?}',
					[
						'as' => 'ledger_party_index',
						'uses' => 'ledger\PartyLedgerManegement@index',
					]);
			});
			Route::group(['prefix' => 'general'], function(){
				Route::get('index/{p?}',
					[
						'as' => 'ledger_general_index',
						'uses' => 'ledger\GeneralLedgerManagement@index',
					]);
			});
		});
		//Party Management
		Route::group(['prefix' => 'party'], function(){
			Route::get('index',
				[
					'as' => 'party_index',
					'uses' => 'Accounts_Head\PartyManagement@indexView',
				]);

			Route::get('add',
				[
					'as' => 'add_new_party',
					'uses' => 'Accounts_Head\PartyManagement@partyView',
				]);

			Route::post('',
				[
					'as' => 'party_add_action',
					'uses' => 'Accounts_Head\PartyManagement@addParty',
				]);
			Route::get('/update/{id?}',
				[
					'as' => 'party_update_view',
					'uses' => 'Accounts_Head\PartyManagement@updateParty',
				]);
			Route::post('/update',
				[
					'as' => 'party_update_action',
					'uses' => 'Accounts_Head\PartyManagement@updateAction',
				]);
			});

		Route::any('invoices/{row}',
		[
			'as' => 'sales_invoice',
			'uses' => 'sale\SaleManagement@generateInvoice',
		]);
	});
});
