<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Message\StatusMessage;
use App\Model\MxpClientCompany;
use App\Model\MxpInvoice;
use App\Model\MxpPacket;
use App\Model\MxpProductPurchase;
use App\Model\MxpTaxvatCals;
use App\Model\Mxp_taxvat;
use App\Model\MxpChartOfAccHead;
use App\MxpProduct;
use App\User;

use App\Http\Controllers\API\AccountHeadApi;
use App\Http\Controllers\API\GeneralData;
use App\Http\Controllers\product\LCPurchaseManagement;

use App\MxpCompany;
use App\MxpProductGroup;
use DB;
use Illuminate\Http\Request;
use Validator;

class PurchaseManagement extends Controller 
{
	public function purchaseTable(Request $request) {

		$vattaxes = Mxp_taxvat::select('id', 'name')->where('status', '=', '1')->get();
		$packets = MxpPacket::select('id', 'name', 'unit_quantity')->where('is_active', '=', '1')->get();

//		$clients=DB::select("SELECT mc.acc_final_name,sc.head_sub_class_name,mc.chart_o_acc_head_id,cl.head_class_name FROM mxp_chart_of_acc_heads mc
//JOIN mxp_acc_head_sub_classes sc ON(mc.mxp_acc_head_sub_classes_id=sc.mxp_acc_head_sub_classes_id)
//JOIN mxp_acc_classes cl ON(cl.mxp_acc_classes_id=mc.mxp_acc_classes_id)
//JOIN mxp_accounts_sub_heads sh ON(sh.accounts_sub_heads_id=mc.accounts_sub_heads_id)
//JOIN mxp_accounts_heads hd ON(hd.accounts_heads_id=mc.accounts_heads_id)
//WHERE cl.mxp_acc_classes_id='83' AND mc.mxp_acc_head_sub_classes_id='94' AND mc.group_id=".self::getGroupId()." AND mc.company_id=".self::getConpanyId()."");

        $clients = $this->getAccHeadClientForPur();

		$productGroup = MxpProductGroup::get()
			->where('com_group_id', '=', self::getGroupId())
			->where('company_id', '=', self::getConpanyId())
			->where('is_active', '=', '1')
			->where('is_deleted', '=', '0');


		$chart_of_accounts = json_decode(AccountHeadApi::chartOfAccount($request));

		if ($request->session()->get('user_type') == 'super_admin') {
			$companies = MxpCompany::get()->where('group_id', self::getGroupId());
		} else if ($request->session()->get('user_type') == 'company_user') {
			$companies = MxpCompany::get()->where('id', self::getConpanyId());
		}

		return view('product.purchase.purchase_table_new', [
			'vattaxes' 			=> 	$vattaxes, 
			'packets' 			=> 	$packets, 
			'clients' 			=> 	$clients, 
			'request' 			=> 	$request, 
			'productGroup' 		=> 	$productGroup,
			'chart_of_accounts' => 	$chart_of_accounts, 
			'companies' 		=> 	$companies]);
	}

	/*public function purchaseTableAdd(Request $request) {

		$validator = Validator::make($request->all(), [
			'invoice.*' => 'required',
			'company_id' => 'required',
			'com_group_id' => 'required',
			'product_id.*' => 'required',
			'client_id.*' => 'required',
			'quantity.*' => 'required',
			'price.*' => 'required',
			'chart_of_acc.*' => 'required',
		]);

		if ($validator->fails()) {
			return redirect()->back()->withInput($request->input())->withErrors($validator->messages());
		}

		if (isset($request->product_id[0])) {
			for ($i = 0; $i < count($request->product_id); $i++) {

				$invoice = new MxpInvoice();
				$invoice->invoice_code = $request->invoice[$i];
				$invoice->client_id = $request->client_id[$i];
				$invoice->company_id = $request->company_id;
				$invoice->com_group_id = $request->com_group_id;
				$invoice->type = 'purchase';
				$invoice->user_id = self::getUserId();
				$invoice->save();
				$invoiceLastId = $invoice->id;

				$tmpBonus = 0;
				if ($request->bonus[$i] == null or $request->bonus[$i] == "") {
					$tmpBonus = 0;
				} else {
					$tmpBonus = $request->bonus[$i];
				}  

				$total_vat = 0;
				foreach ($request->vat_tax[$i] as $key => $value) {
					$total_vat += (($request->quantity[$i] * $request->price[$i]) * $value) / 100;
				}	

				$proPurchase = new MxpProductPurchase();
				$proPurchase->product_id = $request->product_id[$i];
				$proPurchase->invoice_id = $invoiceLastId;
				$proPurchase->client_id = $request->client_id[$i];
				$proPurchase->com_group_id = $request->com_group_id;
				$proPurchase->company_id = $request->company_id;
				$proPurchase->user_id = self::getUserId();
				$proPurchase->quantity = $request->quantity[$i];
				$proPurchase->price = $request->price[$i];
				$proPurchase->bonus = $tmpBonus;
				$proPurchase->is_active = '1';
				$proPurchase->is_deleted = '0';
				$proPurchase->purchase_date = $request->date[$i];
				$proPurchase->due_amount = $request->due_amount[$i];
				$proPurchase->account_head_id = $request->chart_of_acc[$i];

				$randomGroupId = $this->setToJournal($proPurchase, $request->chart_of_acc[$i], $total_vat);

				$proPurchase->jouranl_posting_rand_grp_id = $randomGroupId;
				$proPurchase->save();
				$proPurchaseLastId = $proPurchase->id;

				// self::print_me($request->chart_of_acc[$i]);

				for ($j = 0; $j < count($request->tax_vat_id); $j++) {

					$taxVatCals = new MxpTaxvatCals();
					$taxVatCals->invoice_id = $invoiceLastId;
					$taxVatCals->product_id = $request->product_id[$i];
					$taxVatCals->vat_tax_id = $request->tax_vat_id[$j];
					$taxVatCals->sale_purchase_id = $proPurchaseLastId;
					$taxVatCals->company_id = $request->company_id;
					$taxVatCals->group_id = $request->com_group_id;
					$taxVatCals->user_id = self::getUserId();
					$taxVatCals->total_amount = $request->quantity[$i] * $request->price[$i];
					$taxVatCals->calculate_amount = (($request->quantity[$i] * $request->price[$i]) * $request->vat_tax[$i][$j]) / 100;
					$taxVatCals->type = 'localpurchase';
					$taxVatCals->percent = $request->vat_tax[$i][$j]!=0?$request->vat_tax[$i][$j]:0;
					$taxVatCals->total_amount_with_vat = $total_vat+($request->quantity[$i] * $request->price[$i]);
					$taxVatCals->price_per_unit = $request->price[$i];
					$taxVatCals->save();
				}
			}
		} else {
			StatusMessage::create('product_purchase_warrning', "Row is empty");
			return redirect()->back();
		}

		StatusMessage::create('product_purchase', "Product Purchase Saved Successfully");

		return redirect()->back();
	}*/
	public function purchaseTableAdd(Request $request) {

		$validator = Validator::make($request->all(), [
			'invoice.*' => 'required',
			'company_id' => 'required',
			'com_group_id' => 'required',
			'product_id.*' => 'required',
			'client_id.*' => 'required',
			'quantity.*' => 'required',
			'price.*' => 'required',
			'chart_of_acc.*' => 'required',
		]);

		if ($validator->fails()) {
			return redirect()->back()->withInput($request->input())->withErrors($validator->messages());
		}

		//echo '<pre>';print_r($request->all());exit;
		if (isset($request->product_id[0])) {
			for ($i = 0; $i < count($request->product_id); $i++) {

				$invoice = new MxpInvoice();
				$invoice->invoice_code = $request->invoice[$i];
				$invoice->client_id = $request->client_id[$i];
				$invoice->company_id = $request->company_id;
				$invoice->com_group_id = $request->com_group_id;
				$invoice->type = 'purchase';
				$invoice->user_id = self::getUserId();
				$invoice->save();
				$invoiceLastId = $invoice->id;

				$tmpBonus = 0;
				if ($request->bonus[$i] == null or $request->bonus[$i] == "") {
					$tmpBonus = 0;
				} else {
					$tmpBonus = $request->bonus[$i];
				}  

				$total_vat = 0;
				$total_vat += (($request->quantity[$i] * $request->price[$i]) * $request->vat_percentage[$i] ) / 100;	

				$proPurchase = new MxpProductPurchase();
				$proPurchase->product_id = $request->product_id[$i];
				$proPurchase->invoice_id = $invoiceLastId;
				$proPurchase->client_id = $request->client_id[$i];
				$proPurchase->com_group_id = $request->com_group_id;
				$proPurchase->company_id = $request->company_id;
				$proPurchase->user_id = self::getUserId();
				$proPurchase->quantity = $request->quantity[$i];
				$proPurchase->price = $request->price[$i];
				$proPurchase->bonus = $tmpBonus;
				$proPurchase->is_active = '1';
				$proPurchase->is_deleted = '0';
				$proPurchase->purchase_date = $request->date[$i];
				$proPurchase->due_amount = $request->due_amount[$i];
				$proPurchase->account_head_id = $request->chart_of_acc[$i];

				$randomGroupId = $this->setToJournal($proPurchase, $request->chart_of_acc[$i], $total_vat);

				$proPurchase->jouranl_posting_rand_grp_id = $randomGroupId;
				$proPurchase->save();
				$proPurchaseLastId = $proPurchase->id;

				// self::print_me($request->chart_of_acc[$i]);

				for ($j = 0; $j < count($request->tax_vat_id); $j++) {

					$taxVatCals = new MxpTaxvatCals();
					$taxVatCals->invoice_id = $invoiceLastId;
					$taxVatCals->product_id = $request->product_id[$i];
					$taxVatCals->vat_tax_id = $request->tax_vat_id[$j];
					$taxVatCals->sale_purchase_id = $proPurchaseLastId;
					$taxVatCals->company_id = $request->company_id;
					$taxVatCals->group_id = $request->com_group_id;
					$taxVatCals->user_id = self::getUserId();
					$taxVatCals->total_amount = $request->quantity[$i] * $request->price[$i];
					//self::print_me($request->vat_percentage);
					if($request->tax_vat_id[$j] == 2){
					$taxVatCals->calculate_amount = ($request->quantity[$i] * $request->price[$i] * $request->vat_percentage[$i]) / 100;

					$taxVatCals->percent = $request->vat_percentage[$i]!=0?$request->vat_percentage[$i]:0;
					}

					else{
					$taxVatCals->calculate_amount = ($request->quantity[$i] * $request->price[$i] * $request->tax_percentage[$i]) / 100;

					$taxVatCals->percent = $request->tax_percentage[$i]!=0?$request->tax_percentage[$i]:0;
					}

					$taxVatCals->type = 'localpurchase';
					$taxVatCals->total_amount_with_vat = $total_vat+($request->quantity[$i] * $request->price[$i]);

					$taxVatCals->price_per_unit = $request->price[$i];
					$taxVatCals->save();
				}
			}
		}
		else {
			StatusMessage::create('product_purchase_warrning', "Row is empty");
			return redirect()->back();
		}

		StatusMessage::create('product_purchase', "Product Purchase Saved Successfully");

		return redirect()->back();
	}

	public function purchaseTableVal(Request $request){
			$result=array();
	 		$ary = $request->all();

	 		if(!empty($ary['prch_val'])){
         		$results=json_decode($ary['prch_val']);
         		foreach ($results as $key1 => $values) {
         			foreach ($values as $key => $value1) {
         				$result[$key1][$value1->name]=$value1->value;
         			}
         		
         		}
        	}	 	
       //echo '<pre>';print_r($result);exit;

        $clients = $this->getAccHeadClientForPur();

		$productGroup = MxpProductGroup::get()
			->where('com_group_id', '=', self::getGroupId())
			->where('company_id', '=', self::getConpanyId())
			->where('is_active', '=', '1')
			->where('is_deleted', '=', '0');


		$chart_of_accounts = json_decode(AccountHeadApi::chartOfAccount($request));

		if ($request->session()->get('user_type') == 'super_admin') {
			$companies = MxpCompany::get()->where('group_id', self::getGroupId());
		} else if ($request->session()->get('user_type') == 'company_user') {
			$companies = MxpCompany::get()->where('id', self::getConpanyId());
		}
		$vattaxes = Mxp_taxvat::select('id', 'name')->where('status', '=', '1')->get();

		return view('product.purchase.purchase_table_view',[
			'vattaxes' 		    => $vattaxes,
			'clients' 			=> 	$clients, 
			'productGroup' 		=> 	$productGroup,
			'chart_of_accounts' => 	$chart_of_accounts, 
			'companies' 		=> 	$companies,
			'request' 			=> 	$request])->with('data',$result); 
	}


	private function setToJournal($proPurchase, $chart_of_acc, $total_vat)
	{
		$journalPosting = new LCPurchaseManagement();
		$randomGroupId = self::getRandomGroupId();

		$product = MxpProduct::find($proPurchase->product_id);
//		$client = User::find($proPurchase->client_id);
        $client = MxpChartOfAccHead::find($proPurchase->client_id);



		$paymentType = MxpChartOfAccHead::find($chart_of_acc);

		// self::print_me($paymentType->accountClass->head_class_name); /*this important*/

//		if($paymentType->mxp_acc_classes_id == $this->chartOfAccBank)
//		{
//			$journalPosting->saveToJurnal($this->transaction_type_id,
//				$this->inventoryOrStock,
//				'Inventory or Stock/'.$product->name,
//				'Local Purchase',
//				$proPurchase->quantity*$proPurchase->price+$total_vat, 0,
//				$randomGroupId,
//				$proPurchase->purchase_date,
//				$proPurchase->client_id,
//				$proPurchase->product_id,0,0,
//                $proPurchase->client_id);
//
//			$journalPosting->saveToJurnal($this->transaction_type_id,
//				$chart_of_acc,
//				$paymentType->acc_final_name.'('.$paymentType->accountSubHead->sub_head.')/'.$product->name,
//				'Local Purchase', 0,
//				$proPurchase->quantity*$proPurchase->price+$total_vat,
//				$randomGroupId,
//				$proPurchase->purchase_date,
//				$proPurchase->client_id,
//				$proPurchase->product_id,0,0,
//                $proPurchase->client_id);
//		}
//		else
//		{
			if ($proPurchase->due_amount>0) 
			{
				$journalPosting->saveToJurnal($this->transaction_type_id, 
					$this->inventoryOrStock, 
					$product->name, 
					'Local Purchase', 
					(($proPurchase->quantity*$proPurchase->price)+$total_vat), 0,
					$randomGroupId,
					$proPurchase->purchase_date,
					$proPurchase->client_id,
					$proPurchase->product_id,0,0,
                    $proPurchase->client_id);

				$journalPosting->saveToJurnal($this->transaction_type_id, 
					$chart_of_acc, 
					$paymentType->acc_final_name.'('.$paymentType->accountSubHead->sub_head.')/'.$product->name, 
					'Local Purchase',  0,
					(($proPurchase->quantity*$proPurchase->price)+$total_vat)-$proPurchase->due_amount, 
					$randomGroupId,
					$proPurchase->purchase_date,
					$proPurchase->client_id,
					$proPurchase->product_id,0,0,
                    $proPurchase->client_id);

				$journalPosting->saveToJurnal($this->transaction_type_id, 
					$this->companyLoan, 
					$client->acc_final_name.')/'.$product->name,
					'Local Purchase',  0,
					$proPurchase->due_amount, 
					$randomGroupId,
					$proPurchase->purchase_date,
					$proPurchase->client_id,
					$proPurchase->product_id,0,0,
                    $this->ledger_client_id);
			}
			else
			{
				$journalPosting->saveToJurnal($this->transaction_type_id, 
					$chart_of_acc, 
					'Inventory or Stock/'.$product->name, 
					'Local Purchase', 
					($proPurchase->quantity*$proPurchase->price)+$total_vat, 0, 
					$randomGroupId,$proPurchase->purchase_date,
					$proPurchase->client_id,
					$proPurchase->product_id,0,0,
                    $proPurchase->client_id);

				$journalPosting->saveToJurnal($this->transaction_type_id, 
					$chart_of_acc, 
					$paymentType->acc_final_name.'('.$paymentType->accountSubHead->sub_head.')/'.$product->name, 
					'Local Purchase', 0, 
					($proPurchase->quantity*$proPurchase->price)+$total_vat, 
					$randomGroupId,
					$proPurchase->purchase_date,
					$proPurchase->client_id,
					$proPurchase->product_id,0,0,
                    $proPurchase->client_id);
			}
//		}
		return $randomGroupId;
	}

	public function purchaseList($page = 1) 
	{
        $start              =   ($page-1)*$this->limitPerPage;

        $totalData          =   MxpProductPurchase::where('company_id',self::getConpanyId())->where('com_group_id',self::getGroupId())->where('is_deleted',0)->count();

		$columns = Mxp_taxvat::where('status', 1)->get();

//		$clients = DB::select("select * from mxp_users where type ='client_com' AND group_id = " . self::getGroupId());
		$clients = $this->getAccHeadClientForPur();

		$invoice_codes = DB::select("select * from mxp_invoice where company_id = " . self::getConpanyId() . " AND com_group_id = " . self::getGroupId() . " AND type = 'purchase'");

		$purchase_products = DB::select('call get_all_purchase_products(' . self::getGroupId() . ',' . self::getConpanyId().','.$start.','.$this->limitPerPage . ')');

//		 self::print_me( self::getGroupId() . ',' . self::getConpanyId().','.$start.','.$this->limitPerPage);

		return view('product.purchase.purchase_list', [
			'columns'		=> 	$columns, 
			'purchase_products' => array(), 
			'clients' 		=> 	$clients, 
			'invoice_codes' => 	$invoice_codes,
            'currentPage'   =>  '', 
            'totalData'     =>  $totalData, 
            'limitPerPage'  =>  $this->limitPerPage]);
	}

	public function purchaseSearchList(Request $request) {

		$client = $request->client_id;
		$invoice = $request->invoice_id;
		$dateFrom = $request->dateSearchFldFrom;
		$dateTo = $request->dateSearchFldTo;

		$dataArray = array();

		$query = $this->getQuery($request);

		$purchase_products = DB::select($query . " group by mtvc.sale_purchase_id");

		// self::print_me($query);

		// if ($request->client_id) {
		// 	$query .= "AND mpp.client_id=".$request->client_id;
		// }

		$columns = Mxp_taxvat::where('status', 1)->get();
//		$clients = DB::select("select * from mxp_users where type ='client_com' AND group_id = " . self::getGroupId());
		$clients = $this->getAccHeadClientForPur();

		$invoice_codes = DB::select("select * from mxp_invoice where company_id = " . self::getConpanyId() . " AND com_group_id = " . self::getGroupId());

		$request->session()->put('fromDate', $request->dateSearchFldFrom);
		$request->session()->put('toDate', $request->dateSearchFldTo);
		$request->session()->put('client_id_for_select_field', $request->client_id);
		$request->session()->put('invoice_id_for_select_field', $request->invoice_id);
		$request->session()->put('stock_status', $request->stock_status);

		// self::print_me($purchase_products);

		return view('product.purchase.purchase_list', [
			'columns' => $columns,
			'purchase_products' => $purchase_products,
			'clients' => $clients,
			'invoice_codes' => $invoice_codes,
			'currentPage' => '']);
	}

	public function getProductGroup(Request $request) {
		$pro_grp_id = $request->product_group_id;
		return json_encode(DB::select('call get_product_with_packet_and_unit(' . self::getGroupId() . ',' . self::getConpanyId() . ',' . $pro_grp_id . ')'));

	}

	public function productViewModal(Request $request) 
	{
		$product = DB::select('call get_purchase_productBy_id('.$request->id.')');
		

		$chart_of_acc = MxpChartOfAccHead::find($product[0]->account_head_id);

		// self::print_me($product);

		return view('layouts.pop_up_modal.productViewModal',['product' => $product, 'chart_of_acc' => $chart_of_acc]);

	}

	public function productUpdateModal(Request $request) {

		$purchaseProduct = MxpProductPurchase::find($request->id);

		// $purchaseProduct = DB::select('call get_purchase_productBy_id('.$request->id.')');
		$products = DB::select('call get_product_with_packet_and_unit('.self::getGroupId().','.self::getConpanyId().','.$purchaseProduct->product->product_group_id.')');

		$clients = $this->getAccHeadClientForPur();

		$productGroup = MxpProductGroup::get()
			->where('com_group_id', '=', self::getGroupId())
			->where('company_id', '=', self::getConpanyId())
			->where('is_active', '=', '1')
			->where('is_deleted', '=', '0');

		$chart_of_accounts = json_decode(AccountHeadApi::chartOfAccount($request));
		
		$vattaxes = MxpTaxvatCals::where('sale_purchase_id',$request->id)->where('type','localpurchase')->get();

		// self::print_me(count($vattaxes));
  //echo "<pre>"; print_r($chart_of_accounts);exit;
		return view('layouts.pop_up_modal.purchaseProductUpdateModal',[
			'purchaseProduct'	=>	$purchaseProduct,
			'clients'			=> 	$clients,
			'productGroup'		=> 	$productGroup,
			'vattaxes'			=> 	$vattaxes,
			'products'			=> 	$products,
			'chart_of_accounts'	=> 	$chart_of_accounts]);

	}

	public function proPurchaseUpdateAction(Request $request) {

		$validator = Validator::make($request->all(), [
			'invoice.*' => 'required',
			'product_id.*' => 'required',
			'client_id.*' => 'required',
			'quantity.*' => 'required',
			'price.*' => 'required',
			'chart_of_acc.*' => 'required',
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

		$proPurchase = MxpProductPurchase::find($request->prucahse_product_id);

		$invoice_code_by_id = MxpInvoice::find($proPurchase->invoice_id);
		$invoice_id = 0;

		if($invoice_code_by_id->invoice_code == $request->invoice){
			$invoice_id = $invoice_code_by_id->id;
		}
		else{
			$invoice = new MxpInvoice();
			$invoice->invoice_code = $request->invoice;
			$invoice->client_id = $request->client;
			$invoice->company_id = self::getConpanyId();
			$invoice->com_group_id = self::getConpanyId();
			$invoice->type = 'purchase';
			$invoice->user_id = self::getUserId();
			$invoice->save();
			$invoice_id = $invoice->id;
		}

		$total_vat = 0;
		foreach ($request->vattax_percentage as $key => $value) {
			$total_vat += (($request->quantity * $request->price) * $value) / 100;
		}

		$proPurchase->product_id = $request->product;
		$proPurchase->invoice_id = $invoice_id;
		$proPurchase->client_id = $request->client;
		$proPurchase->com_group_id = self::getGroupId();
		$proPurchase->company_id = self::getConpanyId();
		$proPurchase->user_id = self::getUserId();
		$proPurchase->quantity = $request->quantity;
		$proPurchase->price = $request->price;
		$proPurchase->bonus = $request->bonus;
		$proPurchase->is_active = '1';
		$proPurchase->is_deleted = '0';
		$proPurchase->purchase_date = $request->date;
		$proPurchase->due_amount = $request->due_amount;
		$proPurchase->account_head_id = $request->chart_of_acc;
		$proPurchase->save();

            for ($j = 0; $j < count($request->vattax_id); $j++) {

                $taxVatCals = MxpTaxvatCals::find($request->vattaxcal_id[$j]);
                $taxVatCals->invoice_id = $invoice_id;
                $taxVatCals->product_id = $request->product;
                $taxVatCals->vat_tax_id = $request->vattax_id[$j];
                $taxVatCals->sale_purchase_id = $proPurchase->id;
                $taxVatCals->company_id = self::getConpanyId();
                $taxVatCals->group_id = self::getGroupId();
                $taxVatCals->user_id = self::getUserId();
                $taxVatCals->total_amount = $request->quantity * $request->price;
                $taxVatCals->calculate_amount = (($request->quantity * $request->price) * $request->vattax_percentage[$j]) / 100;
                $taxVatCals->type = 'localpurchase';
                $taxVatCals->percent = $request->vattax_percentage[$j]!=0?$request->vattax_percentage[$j]:0;
                $taxVatCals->total_amount_with_vat = $total_vat+($request->quantity * $request->price);
                $taxVatCals->price_per_unit = $request->price;
                $taxVatCals->save();
            }

		$generalData = new GeneralData();
		$product = $generalData->getProductById($proPurchase->product_id);

		$randomGroupId = $proPurchase->jouranl_posting_rand_grp_id;
//		$client = User::find($proPurchase->client_id);
		$client = MxpChartOfAccHead::find($proPurchase->client_id);
		$paymentType = MxpChartOfAccHead::find($request->chart_of_acc);

		// self::print_me($paymentType);

        if($request->pay_amount > 0)
        {
            $journalPosting = new LCPurchaseManagement();
            $journalPosting->saveToJurnal($this->transaction_type_id,
                $this->companyLoan,
                $client->acc_final_name.'/'.$product,
                'Local Purchase',
                $request->pay_amount, 0,
                $randomGroupId,
                $proPurchase->purchase_date,
                $proPurchase->client_id,
                $proPurchase->product_id,0,0,
                $this->ledger_client_id);


            $journalPosting->saveToJurnal($this->transaction_type_id,
                $proPurchase->account_head_id,
                $paymentType->acc_final_name.'('.$paymentType->accountSubHead->sub_head.')/'.$product,
                'Local Purchase', 0,
                $request->pay_amount,
                $randomGroupId,
                $proPurchase->purchase_date,
                $proPurchase->client_id,
                $proPurchase->product_id,0,0,
                $proPurchase->client_id);


        }
//        return $request->all();
            return "success";
	}

	private function getQuery(Request $request) {
		$query = $this->getBaseQuery($request);

		if ($request->client_id) {
			$query .= " AND mpp.client_id=" . $request->client_id;
		}

		if ($request->invoice_id) {
			$query .= " AND mpp.invoice_id=" . $request->invoice_id;
		}

		if ($request->dateSearchFldFrom) {
			$query .= " AND mpp.purchase_date >= '" . $request->dateSearchFldFrom . "'";
		}

		if ($request->dateSearchFldTo) {
			$query .= " AND mpp.purchase_date <= '" . $request->dateSearchFldTo . "'";
		}

		if ($request->stock_status) {
			$query .= " AND mpp.stock_status = '" . $request->stock_status . "'";
		}

		return $query;
	}

	private function getBaseQuery(Request $request) 
	{
		return "SELECT mpp.*,
             pr.name as product_name,
             pk.name as packet_name,
             pk.quantity as packet_quantity,
             u.name as unit_name,
             pk.unit_quantity,
             mu.acc_final_name as client_name,
             mi.invoice_code,
             mtvc.total_amount_with_vat,
             GROUP_CONCAT(mtvc.calculate_amount) as new_vat_tax,
             GROUP_CONCAT(mt.name) as vat_tax_name,
             GROUP_CONCAT(mtvc.vat_tax_id) as vat_tax_id
             
             FROM mxp_product_purchase mpp
             inner join mxp_product pr on(mpp.product_id = pr.id)
             inner JOIN mxp_packet pk ON(pr.packing_id=pk.id)
             inner join mxp_unit u ON(u.id=pk.unit_id)
             inner join mxp_chart_of_acc_heads mu on(mpp.client_id=mu.chart_o_acc_head_id)
             inner join mxp_invoice mi on(mpp.invoice_id=mi.id)
             inner join mxp_taxvat_cals mtvc on(mtvc.invoice_id = mpp.invoice_id
                AND mtvc.product_id = mpp.product_id
                AND mpp.id = mtvc.sale_purchase_id)
             inner join mxp_taxvats mt on(mtvc.vat_tax_id=mt.id)

			WHERE mpp.com_group_id=" . self::getGroupId() . "
			AND mpp.company_id=" . self::getConpanyId() . "
			AND mpp.is_deleted=0";
	}
}


