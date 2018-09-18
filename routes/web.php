<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::group(
	[
		// 'prefix' => LaravelLocalization::setLocale(),
		//'middleware' => ['localeSessionRedirect', 'localizationRedirect']
	], function () {
		/** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/

		// Multi Language
		Route::get('/language/', array(
			'before' => 'csrf',
			'as' => 'language-chooser',
			'uses' => 'Trans\TranslationController@changeLanguage',

		));

		Route::get('/search_trans_key/', array(
			'as' => 'search_trans',
			'uses' => 'Trans\TransKeyController@searchTransKey',
		));

		Route::get('/comapny_search/', array(
			'as' => 'search_company',
			'uses' => 'RoleManagement@searchCompanyAction',
		));

		Route::any('/product_view_modal/{id?}', array(
			'as' => 'product_modal_view',
			'uses' => 'product\PurchaseManagement@productViewModal',
		));

		Route::any('/product_update_modal/{id?}', array(
			'as' => 'product_modal_update',
			'uses' => 'product\PurchaseManagement@productUpdateModal',
		));

		Route::any('/create_urgent_client_view', array(
			'as' => 'add_urgent_client_view',
			'uses' => 'ClientController@createUrgentClientView',
		));

		Route::any('/create_urgent_client_action', array(
			'as' => 'add_urgent_client_action',
			'uses' => 'ClientController@addUrgentClientAction',
		));


		Route::post('/dghsf/searchLedger',
			[
				'as' => 'ledger_party_search_action',
				'uses' => 'ledger\PartyLedgerManegement@SearchAction',
			]);

		Route::get('/search_purchased_client/{n?}', array(
			'as' => 'search_client',
			'uses' => 'product\PurchasedSearchController@search_client',
		));

		Route::get('/search_purchased_invoice/', array(
			'as' => 'search_invoice',
			'uses' => 'product\PurchasedSearchController@search_invoice',
		));

		Route::get('/search_purchased_date/', array(
			'as' => 'search_date',
			'uses' => 'product\PurchasedSearchController@search_date',
		));

		// Super Admin Login/

		Route::get('/registration', function () {
			return view('auth.register');
		});
		Route::get('/logout', 'Auth\LoginController@logout');

		Auth::routes();

		Route::get('unauthorized', function () {
			return view('notify.unauthorized');
		});

		// Super Admin Access Route
		Route::group(['middleware' => 'auth'], function () {

			Route::get('/', 'HomeController@index');

			Route::get('/dashboard', [
				'as' => 'dashboard_view',
				'uses' => 'HomeController@dashboard',
			]);
			Route::get('/profile', [
				'as' => 'user_profile_view',
				'uses' => 'UserProfileController@profile',
			]);
			Route::post('/profile', [
				'as' => 'user_profile_action',
				'uses' => 'UserProfileController@profileUpdate',
			]);

			Route::group(['middleware' => 'routeAccess'], function () {
				Route::group(['prefix' => 'super-admin'], function () {

					Route::group(['middleware' => 'onlySuperAdmin'], function () {
						//Company Account Management
						Route::get('/opencompanyacc', [
							'as' => 'create_company_acc_view',
							'uses' => 'CompanyManagement@companyAccOpeningForm',
						]);
						Route::post('/opencompanyacc', [
							'as' => 'create_company_acc_action',
							'uses' => 'CompanyManagement@openCompanyAcc',
						]);
						Route::get('companylist', [
							'as' => 'company_list_view',
							'uses' => 'CompanyManagement@companyList',
						]);
						Route::get('/updatecompanyacc/{com_id?}', [
							'as' => 'update_company_acc_view',
							'uses' => 'CompanyManagement@updateCompanyAccForm',
						]);
						Route::post('/updatecompanyacc', [
							'as' => 'update_company_acc_action',
							'uses' => 'CompanyManagement@updateCompanyAcc',
						]);
						Route::get('/deletecompanyacc/{com_id?}', [
							'as' => 'delete_company_acc_action',
							'uses' => 'CompanyManagement@deleteCompanyAcc',
						]);
					});
					// End of onlySuperAdmin Middleware

					// Role Management
					Route::get('/addrole',
						[
							'as' => 'add_role_view',
							'uses' => 'RoleManagement@addRoleForm',
						]);

					Route::post('/addrole',
						[
							'as' => 'add_role_action',
							'uses' => 'RoleManagement@addRole',
						]);
					Route::get('/rolelist',
						[
							'as' => 'role_list_view',
							'uses' => 'RoleManagement@roleList',
						]);
					Route::get('/role/delete/{id?}',
						[
							'as' => 'role_delete_action',
							'uses' => 'RoleManagement@deleteRole',
						]);

					Route::get('/role/update/{id?}',
						[
							'as' => 'role_update_view',
							'uses' => 'RoleManagement@updateForm',
						]);
					Route::post('/role/update/{id?}',
						[
							'as' => 'role_update_action',
							'uses' => 'RoleManagement@updateRole',
						]);

					Route::get('role/permission',
						[
							'as' => 'role_permission_view',
							'uses' => 'RoleManagement@rolePermissionForm',
						]);
					Route::post('role/permission',
						[
							'as' => 'role_permission_action',
							'uses' => 'RoleManagement@rolePermission',
						]);
					Route::get('permissions',
						[
							'as' => 'get_role_permission_view',
							'uses' => 'RoleManagement@getPermissions',
						]);
				
					Route::post('role/permission/update',
						[
							'as' => 'role_permission_update_view',
							'uses' => 'RoleManagement@rolePermissionForm',
						]);

					Route::get('role/permission/list',
						[
							'as' => 'role_permission_list_view',
							'uses' => 'RoleManagement@rolePermissionList',
						]);
					// End of Role Management

					// User Management
					Route::get('user/add',
						[
							'as' => 'create_user_view',
							'uses' => 'UserController@createUserForm',
						]);
					Route::post('user/add',
						[
							'as' => 'create_user_action',
							'uses' => 'UserController@createUser',
						]);

					Route::get('user/list',
						[
							'as' => 'user_list_view',
							'uses' => 'UserController@userList',
						]);
					Route::get('user/update/{id?}',
						[
							'as' => 'company_user_update_view',
							'uses' => 'UserController@updateUserForm',
						]);
					Route::post('user/update',
						[
							'as' => 'company_user_update_action',
							'uses' => 'UserController@updateUser',
						]);
					Route::get('user/delete/{id?}',
						[
							'as' => 'company_user_delete_action',
							'uses' => 'UserController@deleteUser',
						]);

					Route::any('getrolelist', [
						// 'as' => 'get_role_list_view',
						'uses' => 'RoleManagement@getRoleList',
					]);
					// End of user management

				});
				// End of super-admin prefix

				// --------- translation language **********
				Route::group(['prefix' => '_translation'], function () {
					Route::get('/uploadTranslationFile',
						[
							'as' => 'update_language',
							'uses' => 'Trans\UploadFileController@updateFileView',
						]);

					Route::get('/successfullyUploaded',
						[
							'as' => 'sure_upload',
							'uses' => 'Trans\UploadFileController@updateLangFiles',
						]);

					Route::group(['prefix' => 'language'], function () {
						Route::get('/',
							[
								'as' => 'manage_language',
								'uses' => 'Trans\TranslationController@languagesProvider',
							]);

						Route::match(['get', 'post'], '/create',
							[
								'as' => 'create_locale_action',
								'uses' => 'Trans\ManageLocaleController@createLocale',
							]);

						Route::match(['get', 'post'], '/update/{id?}',
							[
								'as' => 'update_locale_action',
								'uses' => 'Trans\ManageLocaleController@updateLocale',
							]);

					});

					Route::group(['prefix' => 'manage'], function () {
						Route::match(['get', 'post'], '/create',
							[
								'as' => 'create_translation_action',
								'uses' => 'Trans\TransKeyController@createTrans',
							]);

						Route::get('/{p?}',
							[
								'as' => 'manage_translation',
								'uses' => 'Trans\TranslationController@manageTranslationKey',
							]);

						Route::get('/update/{id?}',
							[
								'as' => 'update_translation_action',
								'uses' => 'Trans\TransKeyController@updateTrans',
							]);

						Route::post('/updatePost/{id?}',
							[
								'as' => 'update_translation_key_action',
								'uses' => 'Trans\TransKeyController@updatedTransAdd',
							]);

						Route::get('/delete/{id?}',
							[
								'as' => 'delete_translation_action',
								'uses' => 'Trans\TransKeyController@deleteTrans',
							]);
					});
				});
				// --------- end translation language **********

				// --------- start product prefix **********

				Route::group(['prefix' => 'product'], function () {
					Route::get('/unitList',
						[
							'as' => 'unit_list_view',
							'uses' => 'product\UnitController@index',
						]);

					Route::get('/addUnit',
						[
							'as' => 'add_unit_view',
							'uses' => 'product\UnitController@addUnitForm',
						]);

					Route::post('/addUnit',
						[
							'as' => 'add_unit_action',
							'uses' => 'product\UnitController@addUnitAction',
						]);

					Route::get('/editUnit/{id?}',
						[
							'as' => 'edit_unit_view',
							'uses' => 'product\UnitController@editUnitForm',
						]);

					Route::post('/editUnit',
						[
							'as' => 'edit_unit_action',
							'uses' => 'product\UnitController@editUnitAction',
						]);

					Route::get('/deleteUnit/{id?}/{name?}/{is_active?}',
						[
							'as' => 'delete_unit_action',
							'uses' => 'product\UnitController@deleteUnitForm',
						]);

					Route::get('/productGroupList',
						[
							'as' => 'group_product_list_view',
							'uses' => 'product\ProductGroupController@index',
						]);

					Route::get('/addProductGroup',
						[
							'as' => 'add_product_group_view',
							'uses' => 'product\ProductGroupController@addProductGroupForm',
						]);

					Route::post('/addProductGroup',
						[
							'as' => 'add_product_group_action',
							'uses' => 'product\ProductGroupController@addProductGroupAction',
						]);

					Route::get('/editProductGroup/{id?}',
						[
							'as' => 'edit_productGroup_view',
							'uses' => 'product\ProductGroupController@editProductGroupForm',
						]);

					Route::post('/editProductGroup',
						[
							'as' => 'edit_productGroup_action',
							'uses' => 'product\ProductGroupController@editProductGroupAction',
						]);

					Route::get('/deleteProductGroup/{id?}/{name?}/{is_active?}',
						[
							'as' => 'delet_productGroup',
							'uses' => 'product\ProductGroupController@deleteProductGroupForm',
						]);

					Route::get('/productlist/{p?}',
						[
							'as' => 'entry_product_list_view',
							'uses' => 'product\ProductEntryController@index',
						]);

					Route::get('/addProductEntry',
						[
							'as' => 'add_product_entry_view',
							'uses' => 'product\ProductEntryController@createProductForm',
						]);

					Route::post('/entryProduct',
						[
							'as' => 'add_product_action',
							'uses' => 'product\ProductEntryController@createProductAction',
						]);

					Route::get('/deleteProductEntry/{id?}',
						[
							'as' => 'delete_product_entry_action',
							'uses' => 'product\ProductEntryController@deleteProduct',
						]);

					Route::get('/updateProductEntry/{group_id?}',
						[
							'as' => 'edit_product_entry_view',
							'uses' => 'product\ProductEntryController@editProductForm',
						]);

					Route::post('/updateProductEntry',
						[
							'as' => 'edit_product_action',
							'uses' => 'product\ProductEntryController@editProductAction',
						]);

					Route::group(['prefix' => 'packet'], function () {

						Route::get('list',
							[
								'as' => 'packet_list_view',
								'uses' => 'product\PacketProduction@packetList',
							]);
						Route::get('add',
							[
								'as' => 'add_packet_view',
								'uses' => 'product\PacketProduction@addPacketForm',
							]);
						Route::post('add',
							[
								'as' => 'add_packet_action',
								'uses' => 'product\PacketProduction@addPacket',
							]);
						Route::get('delete/{pid?}',
							[
								'as' => 'delete_packet_action',
								'uses' => 'product\PacketProduction@delete',
							]);
						Route::get('update/{pid?}',
							[
								'as' => 'update_packet_view',
								'uses' => 'product\PacketProduction@updateForm',
							]);
						Route::post('update/{pid?}',
							[
								'as' => 'update_packet_action',
								'uses' => 'product\PacketProduction@update',
							]);
					});
					// End of packing

					// Purchase
					Route::get('purchase/list/{p?}',
						[
							'as' => 'product_purchase_list_view',
							'uses' => 'product\PurchaseManagement@purchaseList',
						]);
					Route::get('purchase',
						[
							'as' => 'product_purchase_view',
							'uses' => 'product\PurchaseManagement@purchaseTable',
						]);
					Route::post('purchase/add',
						[
							'as' => 'product_purchase_add',
							'uses' => 'product\PurchaseManagement@purchaseTableAdd',
						]);

					Route::post('purchase/searchList',
						[
							'as' => 'product_purchase_search_list',
							'uses' => 'product\PurchaseManagement@purchaseSearchList',
						]);
					//local purchase
					Route::any('get_product_group',
						[
							'as' => 'get_product_group',
							'uses' => 'product\PurchaseManagement@getProductGroup',
						]);
					//local purchase
					Route::post('purchase/pro_purchase_update_post',
						[
							'as' => 'pro_product_purchase_update_action',
							'uses' => 'product\PurchaseManagement@proPurchaseUpdateAction',
						]);

					// End of Purchase

					// Add VatTax
					Route::get('vattax/list',
						[
							'as' => 'list_vat_tax_view',
							'uses' => 'product\VatTaxController@vatTaxList',
						]);
					Route::get('vattax/add',
						[
							'as' => 'add_vat_tax_view',
							'uses' => 'product\VatTaxController@addVatTaxForm',
						]);
					Route::post('vattax/add',
						[
							'as' => 'add_vat_tax_action',
							'uses' => 'product\VatTaxController@addVatTax',
						]);
					Route::get('vattax/delete/{id?}',
						[
							'as' => 'delete_vat_tax_action',
							'uses' => 'product\VatTaxController@delete',
						]);

				});
				// End of product Prefix

				// Client/Company Information
				Route::get('client_com/list',
					[
						'as' => 'client_com_list_view',
						'uses' => 'ClientController@clientComList',
					]);
				Route::get('client_com/add',
					[
						'as' => 'client_com_add_view',
						'uses' => 'ClientController@createClientComForm',
					]);
				Route::post('client_com/add',
					[
						'as' => 'client_com_add_action',
						'uses' => 'ClientController@createClientCom',
					]);
				Route::get('client_com/update/{id?}',
					[
						'as' => 'client_com_update_view',
						'uses' => 'ClientController@updateClientComForm',
					]);
				Route::post('client_com/update/{id?}',
					[
						'as' => 'client_com_update_action',
						'uses' => 'ClientController@updateClientCom',
					]);
				Route::get('client_com/delete/{id?}',
					[
						'as' => 'client_com_delete_action',
						'uses' => 'ClientController@deleteClientCom',
					]);

				//End of Client/Company Information

				// Stock_Management.............

				Route::group(['prefix' => 'stock_management'], function () {
					Route::get('/storeList',
						[
							'as' => 'store_list_view',
							'uses' => 'Stock_Management\StoreController@index',
						]);

					Route::get('/add_store',
						[
							'as' => 'add_store_view',
							'uses' => 'Stock_Management\StoreController@addStoreForm',
						]);

					Route::post('/add_store',
						[
							'as' => 'add_store_action',
							'uses' => 'Stock_Management\StoreController@addStoreAction',
						]);

					Route::get('/edit_store/{id?}',
						[
							'as' => 'edit_store_view',
							'uses' => 'Stock_Management\StoreController@editStoreForm',
						]);

					Route::post('/edit_store',
						[
							'as' => 'edit_store_action',
							'uses' => 'Stock_Management\StoreController@editStoreAction',
						]);

					Route::get('/delete_store/{id?}',
						[
							'as' => 'delete_store_action',
							'uses' => 'Stock_Management\StoreController@deleteStoreForm',
						]);

					// End Store----------------------------------------
					// Stock Start----------------------------------------

					Route::get('/stock/{p?}',
						[
							'as' => 'stock_view',
							'uses' => 'Stock_Management\StockController@indexStock',
						]);

					Route::any('/save_stock',
						[
							'as' => 'save_stock_action',
							'uses' => 'Stock_Management\StockController@saveStockAction',
						]);

					Route::get('/update_stocks/{p?}',
						[
							'as' => 'update_stocks_view',
							'uses' => 'Stock_Management\StockController@updateStocksForm',
						]);

					Route::post('/update_stocks',
						[
							'as' => 'update_stock_action',
							'uses' => 'Stock_Management\StockController@updateStocksAction',
						]);

				});
			});
			// End of RouteAccess Middleware

		});
		// End of Auth Middleware
	});

//  ******** These are so important for Nabodip.... Please don't remove these... ********//
/*
INSERT INTO `mxp_menu` (`menu_id`, `name`, `route_name`, `description`, `parent_id`, `is_active`, `order_id`, `created_at`, `updated_at`) VALUES
(77, 'Store', 'store_list_view', 'Store entry delete', 83, 1, 0, NULL, NULL),
(78, 'Store Add View', 'add_store_view', 'Store entry update form', 0, 1, 0, NULL, NULL),
(79, 'STORE ADD ACTION', 'add_store_action', 'Store entry update action', 0, 1, 0 , NULL, NULL),
(80, 'Store Edit View', 'edit_store_view', 'Store entry delete', 0, 1, 0, NULL, NULL),
(81, 'STORE EDIT ACTION', 'edit_store_action', 'Store entry update form', 0, 1, 0, NULL, NULL),
(82, 'STORE DELETE ACTION', 'delete_store_action', 'Store entry update action', 0, 1, 0 , NULL, NULL),
'83', 'STOCK_MANAGEMENT', '', 'Stock_Management', '0', '1', '0', NULL, NULL),
(84, 'Stock', 'stock_view', 'Stocks', 83, 1, 1, NULL, NULL);

INSERT INTO `mxp_user_role_menu` (`role_menu_id`, `role_id`, `menu_id`, `company_id`, `is_active`, `created_at`, `updated_at`) VALUES
(486, 1, 77, 0, 1, NULL, NULL),
(487, 1, 78, 0, 1, NULL, NULL),
(488, 1, 79, 0, 1, NULL, NULL),
(489, 1, 80, 0, 1, NULL, NULL),
(490, 1, 81, 0, 1, NULL, NULL),
(491, 1, 82, 0, 1, NULL, NULL),
(492, 1, 83, 0, 1, NULL, NULL),
(493, 1, 84, 0, 1, NULL, NULL);

<option value="" {{ ($stock_status === '')? "selected":"" }} name="stock_status">select</option>

<option value="1" {{ ($stock_status === '1')? "selected":"" }} name="stock_status">Stocked</option>

<option value="0" {{ ($stock_status === '0')? "selected":"" }} name="stock_status">Not yet stocked</option>
</select>
 */