<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Message\StatusMessage;
use App\Model\MxpClientCompany;
use App\Model\MxpInvoice;
use App\Model\MxpLcPurchase;
use App\Model\MxpPacket;
use App\Model\MxpChartOfAccHead;
use App\Model\MxpJournalPosting;
use App\MxpCompany;
use App\MxpProductGroup;
use App\Http\Controllers\API\AccountHeadApi;
use App\Http\Controllers\API\GeneralData;

use DB;
use Excel;
use Illuminate\Http\Request;
use Validator;

class LCPurchaseManagement extends Controller {
	private $loan_acc = 'loan account'; // need to get the loan account for update

	public function lcPurchaseTable(Request $request) {

		$packets = MxpPacket::select('id', 'name', 'unit_quantity')->where('is_active', '=', '1')->get();

//		$clients=DB::select("SELECT mc.acc_final_name,sc.head_sub_class_name,mc.chart_o_acc_head_id,cl.head_class_name FROM mxp_chart_of_acc_heads mc
//JOIN mxp_acc_head_sub_classes sc ON(mc.mxp_acc_head_sub_classes_id=sc.mxp_acc_head_sub_classes_id)
//JOIN mxp_acc_classes cl ON(cl.mxp_acc_classes_id=mc.mxp_acc_classes_id)
//JOIN mxp_accounts_sub_heads sh ON(sh.accounts_sub_heads_id=mc.accounts_sub_heads_id)
//JOIN mxp_accounts_heads hd ON(hd.accounts_heads_id=mc.accounts_heads_id)
//WHERE cl.mxp_acc_classes_id='83' AND mc.mxp_acc_head_sub_classes_id='95' AND mc.group_id=".self::getGroupId()." AND mc.company_id=".self::getConpanyId()."");

        $clients = $this->getAccHeadClientForPur();

		$productGroup = MxpProductGroup::get()
			->where('com_group_id', '=', self::getGroupId())
			->where('company_id', '=', self::getConpanyId())
			->where('is_active', '=', '1')
			->where('is_deleted', '=', '0');

		$lc_Bank_ac=DB::select("SELECT mc.acc_final_name,sc.head_sub_class_name,mc.chart_o_acc_head_id,cl.head_class_name FROM mxp_chart_of_acc_heads mc
JOIN mxp_acc_head_sub_classes sc ON(mc.mxp_acc_head_sub_classes_id=sc.mxp_acc_head_sub_classes_id)
JOIN mxp_acc_classes cl ON(cl.mxp_acc_classes_id=mc.mxp_acc_classes_id)
JOIN mxp_accounts_sub_heads sh ON(sh.accounts_sub_heads_id=mc.accounts_sub_heads_id)
JOIN mxp_accounts_heads hd ON(hd.accounts_heads_id=mc.accounts_heads_id)
WHERE hd.accounts_heads_id='2' and cl.mxp_acc_classes_id='84' AND mc.group_id=".self::getGroupId()." AND mc.company_id=".self::getConpanyId()."");
$lc_Bank_loan=DB::select("SELECT mc.acc_final_name,sc.head_sub_class_name,mc.chart_o_acc_head_id,cl.head_class_name FROM mxp_chart_of_acc_heads mc
JOIN mxp_acc_head_sub_classes sc ON(mc.mxp_acc_head_sub_classes_id=sc.mxp_acc_head_sub_classes_id)
JOIN mxp_acc_classes cl ON(cl.mxp_acc_classes_id=mc.mxp_acc_classes_id)
JOIN mxp_accounts_sub_heads sh ON(sh.accounts_sub_heads_id=mc.accounts_sub_heads_id)
JOIN mxp_accounts_heads hd ON(hd.accounts_heads_id=mc.accounts_heads_id)
WHERE hd.accounts_heads_id='2' and cl.mxp_acc_classes_id='85' AND mc.group_id=".self::getGroupId()." AND mc.company_id=".self::getConpanyId()."");	

		$chart_of_accounts = json_decode(AccountHeadApi::chartOfAccount($request));

		if ($request->session()->get('user_type') == 'super_admin') {
			$companies = MxpCompany::get()->where('group_id', self::getGroupId());
		} else if ($request->session()->get('user_type') == 'company_user') {
			$companies = MxpCompany::get()->where('id', self::getConpanyId());
		}

		return view('product.lc_purchase.lc_purchase_table_new', [
			'packets' 			=> 	$packets,
			'clients' 			=> 	$clients, 
			'request' 			=> 	$request, 
			'productGroup' 		=> 	$productGroup, 
			'chart_of_accounts' => 	$chart_of_accounts, 
			'companies' 		=> 	$companies,
			'lc_Bank_ac'        =>  $lc_Bank_ac,
			'lc_Bank_loan'      =>  $lc_Bank_loan  
			]);
	}

	public function lcPurchaseListView(Request $request) {

		$select_products_in_search = DB::select('call get_all_products('.self::getGroupId().','.self::getConpanyId().',null,null)');

//		$clients = DB::select("select * from mxp_users where type ='client_com' AND group_id = " . self::getGroupId()/*. " AND company_id = " . self::getConpanyId()*/);

        $clients = $this->getAccHeadClientForPur();

		$bank_names_in_search = DB::table('mxp_lc_purchases')->select('bank_name')->groupBy('bank_name')->get();

		$country_origns_in_search = DB::table('mxp_lc_purchases')->select('country_orign')->groupBy('country_orign')->get();

		$lc_nos_in_search = DB::table('mxp_lc_purchases')->select('lc_no')->groupBy('lc_no')->get();

		return view('product.lc_purchase.lc_purchase_list', [
			'products_table' => array(),
			'select_products_in_search' => $select_products_in_search,
			'clients' => $clients,
			'bank_names_in_search' => $bank_names_in_search,
			'country_origns_in_search' => $country_origns_in_search,
			'lc_nos_in_search' => $lc_nos_in_search]);
	}

	public function lcPurchaseSearchList(Request $request) {

		$this->put_data_in_session($request);

		$validator = Validator::make($request->all(), [
			'dateSearchFldFrom' => 'required',
			'dateSearchFldTo' => 'required',
		]);

		if ($validator->fails()) {
			return redirect()->back()->withInput($request->input())->withErrors($validator->messages());
		}

		$query = $this->getQuery($request);

		$products_table = DB::select($query . " GROUP BY mlp.lc_purchase_id");

		$select_products_in_search = DB::select('call get_all_products('.self::getGroupId().','.self::getConpanyId().',null,null)');

		// self::print_me($select_products_in_search);

//		$clients = DB::select("select * from mxp_users where type ='client_com' AND group_id = " . self::getGroupId()/*. " AND company_id = " . self::getConpanyId()*/);

        $clients = $this->getAccHeadClientForPur();

		$bank_names_in_search = DB::table('mxp_lc_purchases')->select('bank_name')->groupBy('bank_name')->get();

		$country_origns_in_search = DB::table('mxp_lc_purchases')->select('country_orign')->groupBy('country_orign')->get();

		$lc_nos_in_search = DB::table('mxp_lc_purchases')->select('lc_no')->groupBy('lc_no')->get();

		return view('product.lc_purchase.lc_purchase_list', [
			'products_table' => $products_table,
			'select_products_in_search' => $select_products_in_search,
			'clients' => $clients,
			'bank_names_in_search' => $bank_names_in_search,
			'country_origns_in_search' => $country_origns_in_search,
			'lc_nos_in_search' => $lc_nos_in_search]);
	}

	public function lcPurchaseSummaryView(Request $request) {
		$lc_pursache_product = DB::select('call get_lc_purchase_product_by_id(' . $request->lc_purchase_product_id . ')');

		return view('layouts.pop_up_modal.lc_purchase_product_view_modal', ['lc_pursache_product' => $lc_pursache_product]);
	}

	public function lcPurchaseTableUpdateView(Request $request) {

//		$clients=DB::select("SELECT mc.acc_final_name,sc.head_sub_class_name,mc.chart_o_acc_head_id,cl.head_class_name FROM mxp_chart_of_acc_heads mc
//JOIN mxp_acc_head_sub_classes sc ON(mc.mxp_acc_head_sub_classes_id=sc.mxp_acc_head_sub_classes_id)
//JOIN mxp_acc_classes cl ON(cl.mxp_acc_classes_id=mc.mxp_acc_classes_id)
//JOIN mxp_accounts_sub_heads sh ON(sh.accounts_sub_heads_id=mc.accounts_sub_heads_id)
//JOIN mxp_accounts_heads hd ON(hd.accounts_heads_id=mc.accounts_heads_id)
//WHERE cl.mxp_acc_classes_id='83' AND mc.mxp_acc_head_sub_classes_id='95' AND mc.group_id=".self::getGroupId()." AND mc.company_id=".self::getConpanyId()."");

        $clients = $this->getAccHeadClientForPur();

		$productGroup = MxpProductGroup::get()
			->where('com_group_id', '=', self::getGroupId())
			->where('company_id', '=', self::getConpanyId())
			->where('is_active', '=', '1')
			->where('is_deleted', '=', '0');

		$lc_pursache_product = DB::select('call get_lc_purchase_product_by_id(' . $request->lc_purchase_product_id . ')');

        $chart_of_accounts=DB::select("SELECT mc.acc_final_name,sc.head_sub_class_name,mc.chart_o_acc_head_id,cl.head_class_name FROM mxp_chart_of_acc_heads mc
JOIN mxp_acc_head_sub_classes sc ON(mc.mxp_acc_head_sub_classes_id=sc.mxp_acc_head_sub_classes_id)
JOIN mxp_acc_classes cl ON(cl.mxp_acc_classes_id=mc.mxp_acc_classes_id)
JOIN mxp_accounts_sub_heads sh ON(sh.accounts_sub_heads_id=mc.accounts_sub_heads_id)
JOIN mxp_accounts_heads hd ON(hd.accounts_heads_id=mc.accounts_heads_id)
WHERE hd.accounts_heads_id='2' and cl.mxp_acc_classes_id='84' AND mc.group_id=".self::getGroupId()." AND mc.company_id=".self::getConpanyId()."");
        $lc_Bank_loan=DB::select("SELECT mc.acc_final_name,sc.head_sub_class_name,mc.chart_o_acc_head_id,cl.head_class_name FROM mxp_chart_of_acc_heads mc
JOIN mxp_acc_head_sub_classes sc ON(mc.mxp_acc_head_sub_classes_id=sc.mxp_acc_head_sub_classes_id)
JOIN mxp_acc_classes cl ON(cl.mxp_acc_classes_id=mc.mxp_acc_classes_id)
JOIN mxp_accounts_sub_heads sh ON(sh.accounts_sub_heads_id=mc.accounts_sub_heads_id)
JOIN mxp_accounts_heads hd ON(hd.accounts_heads_id=mc.accounts_heads_id)
WHERE hd.accounts_heads_id='2' and cl.mxp_acc_classes_id='85' AND mc.group_id=".self::getGroupId()." AND mc.company_id=".self::getConpanyId()."");

//		$chart_of_accounts = json_decode(AccountHeadApi::chartOfAccount($request));

		return  view('layouts.pop_up_modal.lc_purchase_table_update_modal',[
			'clients' 				=> $clients,
			'productGroup' 			=> $productGroup,
			'lc_pursache_product' 	=> $lc_pursache_product,
			'chart_of_accounts' 	=> $chart_of_accounts,
            'lc_Bank_loan'         => $lc_Bank_loan
			]);
		// return "<h1>TEST</h1>";

		// return view('layouts.pop_up_modal.lc_purchase_table_update_modal', [
		// 	'clients' => $clients,
		// 	'productGroup' => $productGroup,
		// 	'lc_pursache_product' => $lc_pursache_product,
		// ]);
	}

	public function lcPurchaseTableInsert(Request $request) {
//echo '<pre>';print_r($request->all());exit;
		$validator = Validator::make($request->all(), [
			// 'invoice.*' => 'required',
			'lc_no.*' => 'required',
			'lc_type' => 'required',
			//'swift_charges' => 'required',
			//'vat.*' => 'required',
			//'bank_commission.*' => 'required',
			//'application_form_charges.*' => 'required',
			'total_margin_in_taka.*' => 'required',
			'lc_amount_in_taka.*' => 'required',
			'due_payment_in_taka.*' => 'required',
			'doller_rate.*' => 'required',
			'product_id.*' => 'required',
			'client_id' => 'required',
			'com_group_id' => 'required',
			'company_id' => 'required',
			'date.*' => 'required',
			'country_of_origin.*' => 'required',
			'quantity' => 'required',
			'lc_open_bank.*' => 'required',
			'lc_loan_bank.*' => 'required',
		]);

		if ($validator->fails()) {
			return redirect()->back()->withInput($request->input())->withErrors($validator->messages());
		}

		if (isset($request->product_id[0])) {
			for ($i = 0; $i < count($request->product_id); $i++) {

				if (!empty($request->invoice[$i])) {
					$invoice = new MxpInvoice();
					$invoice->invoice_code = $request->invoice[$i];
					$invoice->client_id = $request->client_id[$i];
					$invoice->company_id = $request->company_id;
					$invoice->com_group_id = $request->com_group_id;
					$invoice->type = 'purchase';
					$invoice->user_id = $request->session()->get('user_id');
					$invoice->save();
					$invoiceLastId = $invoice->id;
				} else {
					$invoiceLastId = 0;
				}

				$other_cost = ($request->other_cost[$i])? $request->other_cost[$i]:0;

				$pcPurchase = new MxpLcPurchase();

				$pcPurchase->lc_no = $request->lc_no[$i];
				$pcPurchase->lc_type = $request->lc_type[$i];
				$pcPurchase->bank_swift_charge = $request->swift_charges[$i];
				$pcPurchase->vat = $request->vat[$i];
				$pcPurchase->bank_commission = $request->bank_commission[$i];
				$pcPurchase->apk_form_charge = $request->application_form_charges[$i];

				$total_bank_charges = $request->swift_charges[$i] + $request->bank_commission[$i] + $request->application_form_charges[$i] + $request->total_vat[$i];
				$pcPurchase->total_bank_charges = $total_bank_charges;

				$pcPurchase->lc_margin = $request->total_margin_in_taka[$i];
				$pcPurchase->lc_amount_usd = $request->lc_amount[$i];
				$pcPurchase->lc_amount_taka = $request->lc_amount_in_taka[$i];
				$pcPurchase->due_payment = $request->due_payment_in_taka[$i];
				$pcPurchase->others_cost = $other_cost;
				$pcPurchase->total_amount_with_other_cost = $request->due_payment_in_taka[$i] + $request->total_paid_amount_with_other[$i];
				$pcPurchase->total_amount_without_other_cost = $request->due_payment_in_taka[$i] + $request->total_paid_amount_without_other[$i];
				$pcPurchase->total_paid = $request->total_paid_amount_without_other[$i];
				$pcPurchase->dollar_rate = $request->doller_rate[$i];
				$pcPurchase->product_id = $request->product_id[$i];
				$pcPurchase->invoice_id = $invoiceLastId;
				$pcPurchase->client_id = $request->client_id[$i];
				$pcPurchase->com_group_id = $request->com_group_id;
				$pcPurchase->company_id = $request->company_id;
				$pcPurchase->user_id = $request->session()->get('user_id');
				$pcPurchase->quantity = $request->quantity[$i];
				// $pcPurchase->price = "BLANK";
				// $pcPurchase->bonus = "BLANK";
				$pcPurchase->stock_status = '0';
				$pcPurchase->purchase_date = $request->date[$i];
				$pcPurchase->is_deleted = '0';
				$pcPurchase->lc_status = $request->lc_status[$i];
				$pcPurchase->country_orign = $request->country_of_origin[$i];
				$pcPurchase->lc_margin_percent = $request->margin[$i];
				$pcPurchase->account_head_id = $request->lc_open_bank[$i];
                $pcPurchase->lc_loan_account_head_id=$request->lc_loan_bank[$i];
				$chart_of_acc = MxpChartOfAccHead::find($request->lc_open_bank[$i]);
				$chart_of_acc1 = MxpChartOfAccHead::find($request->lc_loan_bank[$i]);
//echo '<pre>';print_r($request->all());exit;
				$randomGroupId = $this->setJournal($pcPurchase,$chart_of_acc->acc_final_name,
				$chart_of_acc1->acc_final_name,$request->lc_open_bank[$i],$request->lc_loan_bank[$i]);
				//$randomGroupId1 = $this->setJournal($pcPurchase, $chart_of_acc_l->acc_final_name);
				
				// self::print_me($request->chart_of_acc[$i]);

				$pcPurchase->jouranl_posting_rand_grp_id = $randomGroupId;
				$pcPurchase->save();
				// var_dump($request->chart_of_acc[$i]."  	df  ");
				 //self::print_me($pcPurchase);
			}
			 //die();
		} else {
			StatusMessage::create('product_purchase_warrning', "Row is empty");
			return redirect()->back();
		}

		StatusMessage::create('product_purchase', "Initial LC Open Successfully");

		return redirect()->back();
	}

	private function setJournal($lcPurchase,
		$chart_of_acc,$loan_account_name,$lc_bank_id,$loan_bank_id)
	{
		$generalData = new GeneralData();
		$product = $generalData->getProductById($lcPurchase->product_id);

		$randomGroupId = self::getRandomGroupId();

		$this->saveToJurnal($this->transaction_type_id, 
			$lc_bank_id, // $lcPurchase->bank_name.'/margin paid', 
			$chart_of_acc.'(L/C Bank)', 
			'L/C Purchase', 
			$lcPurchase->lc_margin, 0, 
			$randomGroupId,
			$lcPurchase->purchase_date,
			$lcPurchase->client_id,
			$lcPurchase->product_id,0,0,
			$this->ledger_client_id);

		$this->saveToJurnal($this->transaction_type_id, 
			$this->cash_in_hand, // 'Cash/'.$product.'/margin paid', 
			'Cash in hand ', 
			'L/C Purchase',0, 
			$lcPurchase->lc_margin, 
			$randomGroupId,
			$lcPurchase->purchase_date,
			$lcPurchase->client_id,
			$lcPurchase->product_id,0,0,
			$this->ledger_client_id);

		$this->saveToJurnal($this->transaction_type_id, 
			$this->inventoryOrStock,//inventory id dynamic put hobe
			'lc/'.$product, 
			'L/C Purchase',
			$lcPurchase->total_amount_with_other_cost, 0,
			$randomGroupId,
			$lcPurchase->purchase_date,
			$lcPurchase->client_id,
			$lcPurchase->product_id,0,0,
            $lcPurchase->client_id);
		
		$this->saveToJurnal($this->transaction_type_id, 
			$lc_bank_id, 
			 $chart_of_acc.'(L/C Bank)', 
			'L/C Purchase',  0, 
			$lcPurchase->total_amount_with_other_cost,
			$randomGroupId,
			$lcPurchase->purchase_date,
			$lcPurchase->client_id,
			$lcPurchase->product_id,0,0,
            $lcPurchase->client_id);

		if($lcPurchase->due_payment > 0)
		{
			$this->saveToJurnal($this->transaction_type_id, 
				$loan_bank_id, 
				$loan_account_name, 
				'L/C Purchase',  0, 
//				$lcPurchase->total_amount_without_other_cost-$lcPurchase->lc_margin,
				$lcPurchase->due_payment,
				$randomGroupId,
				$lcPurchase->purchase_date,
				$lcPurchase->client_id,
				$lcPurchase->product_id,0,0,
				$this->ledger_client_id);

			$this->saveToJurnal($this->transaction_type_id, 
				$lc_bank_id, // $lcPurchase->bank_name.'/margin paid', 
			    $chart_of_acc.'(L/C Bank)', 
				'L/C Purchase',
//				$lcPurchase->total_amount_without_other_cost-$lcPurchase->lc_margin,  0,
				$lcPurchase->due_payment,  0,
				$randomGroupId,
				$lcPurchase->purchase_date,
				$lcPurchase->client_id,
				$lcPurchase->product_id,0,0,
				$this->ledger_client_id);
		}
		return $randomGroupId;
	}

	public function saveToJurnal(
		$lc_type, 
		$chart_of_acc, 
		$jurnalParticular, 
		$jurnalDescription,
		$debit,
		$credit,
		$randomGroupId,
		$journal_date,
		$client_id,
		$product_id,
		$bank_id,
		$branch_id,
		$ledger_client_id){

		$journalPosting = new MxpJournalPosting();

		$journalPosting->transaction_type_id 		= 	$lc_type;
		$journalPosting->account_head_id 			= 	$chart_of_acc;
		$journalPosting->sales_man_id 				= 	/*$lcPurchase->*/null;

		$journalPosting->particular 				= 	$jurnalParticular;
		$journalPosting->description 				= 	$jurnalDescription;

		$journalPosting->transaction_amount_debit 	= 	$debit;
		$journalPosting->transaction_amount_credit 	= 	$credit;
		$journalPosting->reference_id 				=	'1';
		$journalPosting->reference_no 				=	'1';
		$journalPosting->cf_code 					= 	/*$lcPurchase->*/'123';
		$journalPosting->posting_group_id 			=	$randomGroupId;


		$journalPosting->client_id 					=	$client_id;
		$journalPosting->ledger_client_id			=	$ledger_client_id;
		$journalPosting->product_id 				=	$product_id;
		$journalPosting->bank_id 					=	$bank_id;
		$journalPosting->branch_id 					=	$branch_id;
		$journalPosting->journal_date 				=	$journal_date;

		$journalPosting->company_id 				=	session()->get('company_id')|0;
		$journalPosting->group_id 					=	session()->get('group_id');
		$journalPosting->is_deleted 				=	0;
		$journalPosting->is_active 					=	1;
		$journalPosting->save();
	}

	public function lcPurchaseUpdateAction(Request $request)
	{
		$product_id = explode(',', $request->product);

		$total_bank_charges = $request->total_vat + $request->swift_charges + $request->bank_commission + $request->application_form_charges;

//		$total_amount_without_other = ($total_bank_charges + $request->total_margin_in_taka + $request->lc_amount_in_taka + $request->due_payment) - $request->due_payment_in_taka;
		$total_amount_without_other = $total_bank_charges + $request->lc_amount_in_taka;

		$total_amount_with_other = $total_amount_without_other + $request->other_cost;

		$validator = Validator::make($request->all(), [
			// 'invoice' => 'required',
			'lc_no' => 'required',
			// 'lc_type' => 'required',
			'swift_charges' => 'required',
			'vat' => 'required',
			'bank_commission' => 'required',
			'application_form_charges' => 'required',
			'total_margin_in_taka' => 'required',
			'lc_amount' => 'required',
			'doller_rate' => 'required',
			'product' => 'required',
			'client' => 'required',
			'date' => 'required',
			'country_of_origin' => 'required',
			'quantity' => 'required',
			'chart_of_acc' => 'required',
		]);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$result = "";
			foreach ($messages->all('<li>:message</li>') as $message)
            {
                $result .= $message;
            }
			return $result;
		}

		$pcPurchase = MxpLcPurchase::find($request->lc_prucahse_product_id);

		$previous_margin = $pcPurchase->lc_margin;

		$invoice_code_by_id = MxpInvoice::find($pcPurchase->invoice_id);
		$invoice_id = 0;

		if($invoice_code_by_id->invoice_code == $request->invoice){
			$invoice_id = $invoice_code_by_id->id;
		}
		else{
			$invoice = new MxpInvoice();
			$invoice->invoice_code = $request->invoice;
			$invoice->client_id = $request->client;
			$invoice->company_id = $request->session()->get('company_id')|0;
			$invoice->com_group_id = $request->session()->get('group_id');
			$invoice->type = 'purchase';
			$invoice->user_id = $request->session()->get('user_id');
			$invoice->save();
			$invoice_id = $invoice->id;
		}

		$pcPurchase->lc_no = $request->lc_no;
		$pcPurchase->lc_type = $request->lc_type;
		$pcPurchase->bank_swift_charge = $request->swift_charges;
		$pcPurchase->vat = $request->vat;
		$pcPurchase->bank_commission = $request->bank_commission;
		$pcPurchase->apk_form_charge = $request->application_form_charges;
		$pcPurchase->lc_margin = $request->total_margin_in_taka;
		$pcPurchase->lc_amount_usd = $request->lc_amount;
		$pcPurchase->lc_amount_taka = $request->lc_amount_in_taka;
		$pcPurchase->due_payment = $request->due_payment_in_taka;
		$pcPurchase->others_cost = $request->other_cost;
		$pcPurchase->total_amount_with_other_cost = $total_amount_with_other;
		$pcPurchase->total_amount_without_other_cost = $total_amount_without_other;
		$pcPurchase->dollar_rate = $request->doller_rate;
		$pcPurchase->product_id = $product_id[0];
		$pcPurchase->invoice_id = $invoice_id;
		$pcPurchase->client_id = $request->client;
		$pcPurchase->com_group_id = $request->session()->get('group_id');
		$pcPurchase->company_id = $request->session()->get('company_id')|0;
		$pcPurchase->user_id = $request->session()->get('user_id');
		$pcPurchase->quantity = $request->quantity;
		$pcPurchase->total_paid = $total_amount_with_other - $request->due_payment_in_taka;
		$pcPurchase->total_bank_charges = $total_bank_charges;
		// $pcPurchase->price = "BLANK";
		// $pcPurchase->bonus = "BLANK";
		$pcPurchase->stock_status = '0';
		$pcPurchase->purchase_date = $request->date;
		$pcPurchase->is_deleted = '0';
		$pcPurchase->lc_status = $request->lc_Status;
//		$pcPurchase->bank_name = $request->bank_name;
        $pcPurchase->lc_loan_account_head_id = $request->lc_loan_bank;
        $pcPurchase->account_head_id = $request->chart_of_acc;
		$pcPurchase->country_orign = $request->country_of_origin;
		$pcPurchase->lc_margin_percent = $request->margin;
		$pcPurchase->save();

		$generalData = new GeneralData();
		$product = $generalData->getProductById($pcPurchase->product_id);
		
		$randomGroupId = $pcPurchase->jouranl_posting_rand_grp_id;

		if($pcPurchase->lc_margin > $previous_margin)
        {

            $chart_of_acc = MxpChartOfAccHead::find($request->chart_of_acc);
            $loan_account_name = MxpChartOfAccHead::find($request->lc_loan_bank);

            $this->saveToJurnal($pcPurchase->lc_type,
                $request->chart_of_acc,
                $chart_of_acc->acc_final_name.'(L/C Bank)',
                'L/C Purchase',0,
                $pcPurchase->lc_margin-$previous_margin,
                $randomGroupId,
                $pcPurchase->purchase_date,
                $pcPurchase->client_id,
                $pcPurchase->product_id,0,0,
                $this->ledger_client_id);

            $this->saveToJurnal($pcPurchase->lc_type,
                $request->lc_loan_bank,
                $loan_account_name->acc_final_name,
                'L/C Purchase',
                $pcPurchase->lc_margin-$previous_margin, 0,
                $randomGroupId,
                $pcPurchase->purchase_date,
                $pcPurchase->client_id,
                $pcPurchase->product_id,0,0,
                $this->ledger_client_id);
        }

		StatusMessage::create('product_purchase', "Product Purchase Updated Successfully");

		return "success";
		
	}


	private function getQuery(Request $request) {
		$query = $this->getBaseQuery($request);

		if ($request->dateSearchFldFrom) {
			$query .= " AND mlp.purchase_date >=  '" . $request->dateSearchFldFrom . "'";
		}

		if ($request->dateSearchFldTo) {
			$query .= " AND mlp.purchase_date <=  '" . $request->dateSearchFldTo . "'";
		}

		if ($request->lcAmountUsd) {
			$query .= " AND mlp.lc_amount_usd=" . $request->lcAmountUsd;
		}

		if ($request->client) {
			$query .= " AND mlp.client_id = " . $request->client;
		}

		if ($request->product_name) {
			$query .= " AND mlp.product_id=" . $request->product_name;
		}

		if ($request->product_code) {
			$query .= " AND mlp.product_id=" . $request->product_code;
		}

		if ($request->bank_name) {
			$query .= " AND mlp.bank_name= '" . $request->bank_name . "'";
		}

		if ($request->country_orign) {
			$query .= " AND mlp.country_orign= '" . $request->country_orign . "'";
		}

		if ($request->lc_no) {
			$query .= " AND mlp.lc_no = '" . $request->lc_no . "'";
		}

		return $query;
	}

	private function getBaseQuery(Request $request) {

		return "SELECT mlp.*,
			GROUP_CONCAT(pr.name,'(',pk.name,'(',pk.quantity,') ',un.name,'(',pk.unit_quantity,'))') as product_details,
			ca.acc_final_name as client_details

			FROM mxp_lc_purchases mlp
			inner join mxp_product pr on(mlp.product_id = pr.id)
			inner JOIN mxp_packet pk ON(pr.packing_id=pk.id)
			inner join mxp_unit un ON(un.id=pk.unit_id)
			inner join mxp_chart_of_acc_heads ca on(ca.chart_o_acc_head_id=mlp.client_id)

			WHERE mlp.com_group_id=" . self::getGroupId() . "
			AND mlp.company_id=" . self::getConpanyId() . "
			AND mlp.is_deleted=0";
	}

	private function put_data_in_session(Request $request) {

		$request->session()->put('dateSearchFldOpenning', $request->dateSearchFldOpenning);
		$request->session()->put('dateSearchFldFrom', $request->dateSearchFldFrom);
		$request->session()->put('dateSearchFldTo', $request->dateSearchFldTo);
		$request->session()->put('lcAmountUsd', $request->lcAmountUsd);
		$request->session()->put('client', $request->client);
		$request->session()->put('product_name', $request->product_name);
		$request->session()->put('product_code', $request->product_code);
		$request->session()->put('bank_name', $request->bank_name);
		$request->session()->put('country_orign', $request->country_orign);
		$request->session()->put('lc_no', $request->lc_no);
	}


	public function excel(Request $request) {

        $lc_export_excel = DB::select('call get_lc_purchase_for_excel_by_date("'.$request->dateSearchFldFrom.'","'.$request->dateSearchFldTo.'")');

	    $export=array();
	    foreach ($lc_export_excel as $value) {
	        $export[] = (array)$value;  
	    }

  //       $query = $this->getQuery($request);

		// $lc_export_excel = DB::select($query . " GROUP BY mlp.lc_purchase_id");

  //    	$export=array(['Lc no/id','Open date','L/C value', 'Margin', 'Bank charges', 'Custom duty other cost', 'Total cost', 'Paid amount', 'Due amount', 'Product details', 'Client details']);
  //    	$i = 1;

	 // 	foreach ($lc_export_excel as $key => $value) 
	 // 	{
	 // 		$export[$i]['Lc no/id'] = $value->lc_no;
	 // 		$export[$i]['Open date'] = $value->purchase_date;
	 // 		$export[$i]['L/C value'] = $value->lc_amount_taka;
	 // 		$export[$i]['Margin'] = $value->lc_margin;
	 // 		$export[$i]['Bank charges'] = $value->total_bank_charges;
	 // 		$export[$i]['Custom duty other cost'] = $value->others_cost;
	 // 		$export[$i]['Total cost'] = $value->total_amount_with_other_cost;
	 // 		$export[$i]['Paid amount'] = $value->total_paid;
	 // 		$export[$i]['Due amount'] = $value->due_payment;
	 // 		$export[$i]['Product details'] = $value->product_details;
	 // 		$export[$i]['Client details'] = $value->client_details;
	 // 		$i++;
	 //    }

    	$items = $export;
      	Excel::create('lc-sumarry', function($excel) use($items) {
          $excel->sheet('ExportFile', function($sheet) use($items) {
              $sheet->fromArray($items);
          });
      	})->export($request->type);
   }

}
