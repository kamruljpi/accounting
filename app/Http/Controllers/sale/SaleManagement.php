<?php

namespace App\Http\Controllers\sale;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Message\StatusMessage;
use App\Model\MxpClientCompany;
use App\Model\MxpInvoice;
use App\Model\MxpPacket;
use App\Model\MxpTaxvatCals;
use App\Model\Mxp_taxvat;
use App\Model\MxpChartOfAccHead;
use App\Model\MxpStock;
use App\Http\Controllers\API\GeneralData;
use App\MxpCompany;
use App\MxpProductGroup;
use App\MxpSaleProduct;
use App\MxpTransport;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\product\LCPurchaseManagement;
use Validator;

class SaleManagement extends Controller {

	
	public function saleTableForm(Request $request) {

		$vattaxes = Mxp_taxvat::select('id', 'name')->where('status', '=', '1')->get();
		$packets = MxpPacket::select('id', 'name', 'unit_quantity')->where('is_active', '=', '1')->get();

		 $clients=DB::select("SELECT mc.acc_final_name,sc.head_sub_class_name,mc.chart_o_acc_head_id,cl.head_class_name FROM mxp_chart_of_acc_heads mc
JOIN mxp_acc_head_sub_classes sc ON(mc.mxp_acc_head_sub_classes_id=sc.mxp_acc_head_sub_classes_id)
JOIN mxp_acc_classes cl ON(cl.mxp_acc_classes_id=mc.mxp_acc_classes_id)
JOIN mxp_accounts_sub_heads sh ON(sh.accounts_sub_heads_id=mc.accounts_sub_heads_id)
JOIN mxp_accounts_heads hd ON(hd.accounts_heads_id=mc.accounts_heads_id)
WHERE cl.mxp_acc_classes_id='83' AND mc.mxp_acc_head_sub_classes_id='94' AND mc.group_id=".self::getGroupId()." AND mc.company_id=".self::getConpanyId()."");
		
		//$clients = MxpChartOfAccHead::get()->where('company_id', self::getConpanyId())->where('group_id', self::getGroupId())/*->where('mxp_acc_head_sub_classes_id', $this->subClaasForSale)*/;
		$chartOfAccs = MxpChartOfAccHead::get()->where('company_id', self::getConpanyId())->where('group_id', self::getGroupId());

		$productGroup = MxpProductGroup::get()
			->where('com_group_id', '=', self::getGroupId())
			->where('company_id', '=', self::getConpanyId())
			->where('is_active', '=', '1')
			->where('is_deleted', '=', '0');

		if ($request->session()->get('user_type') == 'super_admin') {
			$companies = MxpCompany::get()->where('group_id', self::getGroupId());
		} else if ($request->session()->get('user_type') == 'company_user') {
			$companies = MxpCompany::get()->where('id', self::getConpanyId());
		}

		$today = date("Ymd");
		$rand = strtoupper(substr(uniqid(sha1(time())),0,4));
		$unique = $today . $rand;

		return view('product.sale.sale_table', ['vattaxes' => $vattaxes, 'packets' => $packets, 'clients' => $clients, 'request' => $request, 'productGroup' => $productGroup, 'companies' => $companies, 'chartOfAccs' => $chartOfAccs,    'unique' => $unique]);
	}

	public function saleList($page = 1) 
	{
        $start              =   ($page-1)*$this->limitPerPage;

        $totalData          =   MxpSaleProduct::where('company_id',self::getConpanyId())->where('com_group_id',self::getGroupId())->where('is_deleted',0)->count();

		$columns = Mxp_taxvat::where('status', 1)->get();
		$sales_products = DB::select('call get_all_sales_product(' . self::getGroupId() . ',' . self::getConpanyId().','.$start.','.$this->limitPerPage . ')');

//		$this->print_me($sales_products);

		//$clients = DB::select("select * from mxp_users where type ='client_com' AND group_id = " . self::getGroupId());
$clients=DB::select("SELECT mc.acc_final_name,sc.head_sub_class_name,mc.chart_o_acc_head_id,cl.head_class_name FROM mxp_chart_of_acc_heads mc
JOIN mxp_acc_head_sub_classes sc ON(mc.mxp_acc_head_sub_classes_id=sc.mxp_acc_head_sub_classes_id)
JOIN mxp_acc_classes cl ON(cl.mxp_acc_classes_id=mc.mxp_acc_classes_id)
JOIN mxp_accounts_sub_heads sh ON(sh.accounts_sub_heads_id=mc.accounts_sub_heads_id)
JOIN mxp_accounts_heads hd ON(hd.accounts_heads_id=mc.accounts_heads_id)
WHERE cl.mxp_acc_classes_id='83' AND mc.mxp_acc_head_sub_classes_id='94' AND mc.group_id=".self::getGroupId()." AND mc.company_id=".self::getConpanyId()."");
		
		$invoice_codes = DB::select("select * from mxp_invoice where company_id = " . self::getConpanyId() . " AND com_group_id = " . self::getGroupId() . " AND type = 'sale'");

		// $sale_products = DB::select('call get_all_sale_products(' . self::getGroupId() . ',' . self::getConpanyId() . ',' . self::getUserId() . ')');

		return view('product.sale.sale_list', [
			'columns' 		=> 	$columns, 
			'sales_products'=> 	$sales_products, 
			'clients' 		=> 	$clients, 
			'invoice_codes' => 	$invoice_codes,
            'currentPage'   =>  $page, 
            'totalData'     =>  $totalData, 
            'limitPerPage'  =>  $this->limitPerPage]);
	}

	public function frm_value(Request $request){

				$result=array();
		 		$arr = $request->all();

		 		if(!empty($arr['arr_val'])){
	         		$results=json_decode($arr['arr_val']);
	         		foreach ($results as $key1 => $values) {
	         			foreach ($values as $key => $value1) {
	         				$result[$key1][$value1->name]=$value1->value;
	         			}
	         		
	         		}
	         		
	        	}	
	        $result = array_map("unserialize", array_unique(array_map("serialize", $result))); 	
	         /* echo '<pre>';print_r($result);
	          exit;*/
	        $vattaxes = Mxp_taxvat::select('id', 'name')->where('status', '=', '1')->get(); 
	         $clients=DB::select("SELECT mc.acc_final_name,sc.head_sub_class_name,mc.chart_o_acc_head_id,cl.head_class_name FROM mxp_chart_of_acc_heads mc
				JOIN mxp_acc_head_sub_classes sc ON(mc.mxp_acc_head_sub_classes_id=sc.mxp_acc_head_sub_classes_id)
				JOIN mxp_acc_classes cl ON(cl.mxp_acc_classes_id=mc.mxp_acc_classes_id)
				JOIN mxp_accounts_sub_heads sh ON(sh.accounts_sub_heads_id=mc.accounts_sub_heads_id)
				JOIN mxp_accounts_heads hd ON(hd.accounts_heads_id=mc.accounts_heads_id)
				WHERE cl.mxp_acc_classes_id='83' AND mc.mxp_acc_head_sub_classes_id='94' AND mc.group_id=".self::getGroupId()." AND mc.company_id=".self::getConpanyId()."");
	         $chartOfAccs = MxpChartOfAccHead::get()->where('company_id', self::getConpanyId())->where('group_id', self::getGroupId());

	         $productGroup = MxpProductGroup::get()
				->where('com_group_id', '=', self::getGroupId())
				->where('company_id', '=', self::getConpanyId())
				->where('is_active', '=', '1')
				->where('is_deleted', '=', '0');

			if ($request->session()->get('user_type') == 'super_admin') {
				$companies = MxpCompany::get()->where('group_id', self::getGroupId());
			} else if ($request->session()->get('user_type') == 'company_user') {
				$companies = MxpCompany::get()->where('id', self::getConpanyId());
			}


		 	return view('product.sale.sale_add_form',['vattaxes'=>$vattaxes, 'clients' => $clients, 'request' => $request, 'productGroup' => $productGroup, 'companies' => $companies])->with('data',$result);
	}

	public function saleTableAdd(Request $request) {
//echo '<pre>';print_r($request->all());exit;
		$validator = Validator::make($request->all(), [
			'invoice_no.*' => 'required',
			'company_id' => 'required',
			'com_group_id' => 'required',
			'product_id.*' => 'required',
			'client_id.*' => 'required',
			'quantity.*' => 'required',
			'sales_price.*' => 'required',
			'transport_name.*' => 'required',
			'transport_no.*' => 'required',
			'transport_date.*' => 'required',
			'sales_date.*' => 'required',
		    'chartOfAcc.*'=> 'required',	
		    'due'=> 'required',	
		]);
		if ($validator->fails()) {
			return redirect()->back()->withInput($request->input())->withErrors($validator->messages());
		}

//		$this->print_me($request->all());

		for ($i = 0; $i < count($request->invoice_no); $i++) {

			$invoice = new MxpInvoice();
			$invoice->invoice_code = $request->invoice_no[$i];
			$invoice->client_id = $request->client_id[$i];
			$invoice->company_id = $request->company_id;
			$invoice->com_group_id = $request->com_group_id;
			$invoice->type = 'sale';
			$invoice->user_id = self::getUserId();
			$invoice->order_no=$request->order_no;
			$invoice->save();
			$invoiceLastId = $invoice->id;

			$tmpBonus = 0;
			if ($request->bonus[$i] == null or $request->bonus[$i] == "") {
				$tmpBonus = 0;
			} else {
				$tmpBonus = $request->bonus[$i];
			}
			 $this->proDeduction($request->product_id[$i], $request->quantity[$i]+$tmpBonus,$request->sales_price[$i]);
			$total_vat=0;
			if($request->vattax_percentage>0){
            $total_vat = ((($request->quantity[$i] * $request->sales_price[$i])-$request->due[$i]) * $request->vattax_percentage[$i]) / 100;
			}
				
//			foreach ($request->vat_tax[$i] as $key => $value) {
//				 $total_vat += (($request->quantity[$i] * $request->sales_price[$i]) * $value) / 100;
//
//			}

			$proSale = new MxpSaleProduct();
			$proSale->product_id = $request->product_id[$i];
			$proSale->invoice_id = $invoiceLastId;
			$proSale->client_id = $request->client_id[$i];
			$proSale->com_group_id = $request->com_group_id;
			$proSale->company_id = $request->company_id;
			$proSale->user_id = self::getUserId();
			$proSale->quantity = $request->quantity[$i];
			$proSale->price = $request->sales_price[$i];
			$proSale->bonus = $tmpBonus;
			$proSale->vat = $total_vat;
			$proSale->total_amount_w_vat = $total_vat+($request->quantity[$i] * $request->sales_price[$i])-$request->due[$i];
			// $proSale->commission = $request->
			$proSale->sale_date = $request->sales_date[$i];
			$proSale->is_deleted = '0';
			$proSale->is_active = '1';
			$proSale->due_ammount = $request->due[$i];
			 $proSale->save();
			$proSaleLastId = $proSale->id;

			$transPort = new MxpTransport();
			$transPort->transport_name = $request->transport_name[$i];
			$transPort->transport_number = $request->transport_no[$i];
			$transPort->transport_date = $request->transport_date[$i];
			$transPort->invoice_id = $invoiceLastId;
			$transPort->product_id = $request->product_id[$i];
			$transPort->company_id = $request->company_id;
			$transPort->group_id = $request->com_group_id;
			$transPort->user_id = self::getUserId();
			//$transPort->is_deleted = $request->;
			 $transPort->save();

            $taxVatCals = new MxpTaxvatCals();
            $taxVatCals->invoice_id = $invoiceLastId;
            $taxVatCals->product_id = $request->product_id[$i];
            $taxVatCals->vat_tax_id = $this->vat_id;
            $taxVatCals->sale_purchase_id = $proSaleLastId;
            $taxVatCals->company_id = $request->company_id;
            $taxVatCals->group_id = $request->com_group_id;
            $taxVatCals->user_id = self::getUserId();
            $taxVatCals->total_amount = $request->quantity[$i] * $request->sales_price[$i]-$request->due[$i];
            $taxVatCals->calculate_amount = $total_vat;
            $taxVatCals->type = 'sale';
            if ($request->vattax_percentage[$i] == null or $request->vattax_percentage[$i] == "") {
				$taxVatCals->percent = 0;
			}
			else {
				$taxVatCals->percent = $request->vattax_percentage[$i];
			}
            
            $taxVatCals->total_amount_with_vat = $total_vat+($request->quantity[$i] * $request->sales_price[$i])-$request->due[$i];
            $taxVatCals->price_per_unit = $request->sales_price[$i];
            $taxVatCals->save();

			

			$this->setToJournal($proSale, $request->chartOfAcc[$i]);
			

		}
		

		StatusMessage::create('product_sale', "Product Sales Saved Successfully");

		return redirect()->route('sales_invoice', ['row' =>$request->invoice_no[0]]);
		

	}

	public function generateInvoice($invoice_code){
		$get_invoice=DB::select("select inv.invoice_code,inv.order_no,s.*,ch.acc_final_name as client_name,
		p.name as product_name,pk.name as packet_name,pk.quantity as packetsqty,pk.unit_quantity,u.name as unit_name,pk.unit_id
		FROM mxp_sale_products s
		JOIN mxp_invoice inv ON(s.invoice_id=inv.id)
		JOIN mxp_chart_of_acc_heads ch ON(ch.chart_o_acc_head_id=s.client_id)
		JOIN mxp_product p ON(p.id=s.product_id)
		JOIN mxp_packet pk ON(pk.id=p.packing_id)
		JOIN mxp_unit u ON(u.id=pk.unit_id)
		WHERE inv.invoice_code='".$invoice_code."'");
		//echo '<pre>';print_r($get_invoice);exit;
		return view('invoices.sale_invoice',['invoices_op' => $get_invoice]);
	}
   public static function getnumbertoWord($num)
   {
    $num    = ( string ) ( ( int ) $num );
   
    if( ( int ) ( $num ) && ctype_digit( $num ) )
    {
        $words  = array( );
       
        $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
       
        $list1  = array('','one','two','three','four','five','six','seven',
            'eight','nine','ten','eleven','twelve','thirteen','fourteen',
            'fifteen','sixteen','seventeen','eighteen','nineteen');
       
        $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
            'seventy','eighty','ninety','hundred');
       
        $list3  = array('','thousand','million','billion','trillion',
            'quadrillion','quintillion','sextillion','septillion',
            'octillion','nonillion','decillion','undecillion',
            'duodecillion','tredecillion','quattuordecillion',
            'quindecillion','sexdecillion','septendecillion',
            'octodecillion','novemdecillion','vigintillion');
       
        $num_length = strlen( $num );
        $levels = ( int ) ( ( $num_length + 2 ) / 3 );
        $max_length = $levels * 3;
        $num    = substr( '00'.$num , -$max_length );
        $num_levels = str_split( $num , 3 );
       
        foreach( $num_levels as $num_part )
        {
            $levels--;
            $hundreds   = ( int ) ( $num_part / 100 );
            $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
            $tens       = ( int ) ( $num_part % 100 );
            $singles    = '';
           
            if( $tens < 20 )
            {
                $tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
            }
            else
            {
                $tens   = ( int ) ( $tens / 10 );
                $tens   = ' ' . $list2[$tens] . ' ';
                $singles    = ( int ) ( $num_part % 10 );
                $singles    = ' ' . $list1[$singles] . ' ';
            }
            $words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        }
       
        $commas = count( $words );
       
        if( $commas > 1 )
        {
            $commas = $commas - 1;
        }
       
        $words  = implode( ', ' , $words );
       $what   = "\\x00-\\x20";
       $with = ' ';
        //Some Finishing Touch
        //Replacing multiples of spaces with one space
        $words  = trim( str_replace( ' ,' , ',' , trim( preg_replace( "/[".$what."]+/" , $with , ucwords($words) ) , $what ) ) , ', ' );

        if( $commas )
        {
            $words  = str_replace_last( ',' , ' and' , $words );
        }
       
        return $words;
    }
    else if( ! ( ( int ) $num ) )
    {
        return 'Zero';
    }
    return '';
}


    public function str_replace_last( $search , $replace , $str ) {
        if( ( $pos = strrpos( $str , $search ) ) !== false ) {
            $search_length  = strlen( $search );
            $str    = substr_replace( $str , $replace , $pos , $search_length );
        }
        return $str;
    }

	private function setToJournal($proSale, $chartOfAcc) 
	{
//echo '<pre>';print_r($proSale->due_ammount);exit;

		$generalData = new GeneralData();
		$product = $generalData->getProductById($proSale->product_id);
		// self::print_me($product);

		$inventory = MxpChartOfAccHead::find($this->inventoryOrStock);
		$costOfGood = MxpChartOfAccHead::find($this->cost_of_good_sold);

		$chartOfAccData = MxpChartOfAccHead::find($chartOfAcc);
		$client = MxpChartOfAccHead::find($proSale->client_id);

		$randomGroupId = self::getRandomGroupId();

		$stocks = MxpStock::where('product_id',$proSale->product_id)->get();

		$journal = new LCPurchaseManagement();
        $this->ledger_client_id=$proSale->client_id;

        if ($proSale->due_ammount>0) 
			{

			$journal->saveToJurnal(
			$this->transaction_type_id,
			 $this->companyLoan,
			'party('.$client->acc_final_name.')',
			'Sale Products', 
			0,
			$proSale->due_ammount,
			$randomGroupId,
			$proSale->sale_date,
			$proSale->client_id,
			$proSale->product_id,0,0,
			$this->ledger_client_id);

			}
			$t_price=$proSale->quantity*$proSale->price;
			$av_price=$stocks[0]->avg_price_unit*$proSale->quantity;
		$journal->saveToJurnal($this->transaction_type_id,
			$chartOfAcc,
			$chartOfAccData->acc_final_name,
			'Sale Products',
			$t_price-$proSale->due_ammount,
			  0,
			$randomGroupId,
			$proSale->sale_date,
			$proSale->client_id,
			$proSale->product_id,0,0,
			$this->ledger_client_id);

		$journal->saveToJurnal($this->transaction_type_id,
			$proSale->client_id,
			'party('.$client->acc_final_name.')',
			'Sale Products', 0,
			$t_price-$proSale->due_ammount,
			$randomGroupId,
			$proSale->sale_date,
			$proSale->client_id,
			$proSale->product_id,0,0,
			$this->ledger_client_id);

		$journal->saveToJurnal($this->transaction_type_id,
			$this->chartOfSale,
			'Sale',
			'Sale Products',  0,
			$t_price-$proSale->due_ammount,
			$randomGroupId,
			$proSale->sale_date,
			$proSale->client_id,
			$proSale->product_id,0,0,
			$this->ledger_client_id);

		$journal->saveToJurnal($this->transaction_type_id,
			$proSale->client_id,
			'party('.$client->acc_final_name.')',
			'Sale Products',
			$t_price-$proSale->due_ammount, 0,
			$randomGroupId,
			$proSale->sale_date,
			$proSale->client_id,
			$proSale->product_id,0,0,
			$this->ledger_client_id);

		$journal->saveToJurnal($this->transaction_type_id, 
			$this->cost_of_good_sold, 
			$costOfGood->acc_final_name.'/'.$product,
			'Sale Products',
			$av_price-$proSale->due_ammount, 0,
			$randomGroupId,
			$proSale->sale_date,
			$proSale->client_id,
			$proSale->product_id,0,0,
			$this->ledger_client_id);

		$journal->saveToJurnal($this->transaction_type_id, 
			$this->inventoryOrStock, 
			$inventory->acc_final_name.'/'.$product,
			'Sale Products',  0,
			$av_price-$proSale->due_ammount,
			$randomGroupId,
			$proSale->sale_date,
			$proSale->client_id,
			$proSale->product_id,0,0,
			$this->ledger_client_id);
	}

	public function getProductGroup(Request $request) {

		$pro_grp_id = $request->product_group_id;

		return json_encode(DB::select('call get_all_stocks_by_productGrpId(' . self::getGroupId() . ',' . self::getConpanyId() . ',' . $pro_grp_id . ')'));

	}

	public function getProductGroupLc(Request $request) {

		$pro_grp_id = $request->product_group_id;

		return json_encode(DB::select('call get_all_lc_stocks_by_productGrpId(' . self::getGroupId() . ',' . self::getConpanyId() . ',' . $pro_grp_id . ')'));

	}

	public function getLastSaleId() {
		return json_encode(DB::table('mxp_sale_products')->select('id')->orderBy('id', 'desc')->first());
	}

	public function checkAmount(Request $request) {

		$checkAvail=DB::table('mxp_stock')->select(DB::raw("SUM(available_quantity) as available_quantity"))
		->where('product_id', "=", $request->pro_id)->orderBy('id', 'ASC')->groupBy('product_id')->first();
		
		return json_encode($checkAvail);
	}

	public function inventoryReport_view(Request $request) {

		$available_quantity = DB::select("SELECT mp.name as product_name,
		ms.available_quantity as available_quantity,
		SUM(ms.quantity) as quantity,
		 mpg.name as group_name,
		 mpk.name as packet_name, mpk.quantity as packet_quantity,
		  munit.name as unit_name, mpk.unit_quantity,ms.avg_price_unit,ms.sale_price,
		  ms.sale_quantity,ms.created_at
		  FROM mxp_product mp
		  LEFT JOIN mxp_packet mpk ON(mpk.id = mp.packing_id)
		  LEFT JOIN mxp_unit munit ON(munit.id = mpk.unit_id)
		  LEFT JOIN mxp_stock ms ON(mp.id = ms.product_id)
		   LEFT JOIN mxp_product_group mpg ON mpg.id= mp.product_group_id
		    WHERE ms.company_id = " . self::getConpanyId() . "
		     AND ms.com_group_id = " . self::getGroupId() . " AND ms.is_deleted='0' GROUP BY ms.lot_id order by ms.id");

		return view('product.sale.available_quantity', ['available_quantity' => $available_quantity]);
	}

	public function proDeduction($product_id,$product_quantity,$salePrice,$lot_id=0,$fIfo=0) {
		if($lot_id==0){
			$get_avail=MxpStock::select('available_quantity','lot_id')->where('product_id',$product_id)->where('available_quantity','>',0)->orderBy('id', 'ASC')->first();
		}else{
			$get_avail=MxpStock::select('available_quantity','lot_id')->where('product_id',$product_id)->where('available_quantity','>',0)->where('lot_id','!=',$lot_id)->orderBy('id', 'ASC')->first();
		}
		 if($get_avail->available_quantity<$product_quantity){
		 	$final_qtn=$product_quantity-$get_avail->available_quantity;
		 	DB::select("UPDATE mxp_stock SET available_quantity = 0,sale_quantity=".$get_avail->available_quantity.",
		 	sale_price= ".$salePrice." WHERE lot_id = " . $get_avail->lot_id);
		 	/*MxpStock::where('lot_id',$get_avail->lot_id)
			->update([
				'sale_price'=>$salePrice,
				//'sale_quantity'=>$request->quantity[$i]
				]);*/
		 	$this->proDeduction($product_id,$final_qtn,$salePrice,$get_avail->lot_id);
		 }else{
		 	DB::select("UPDATE mxp_stock SET available_quantity = abs(available_quantity-".$product_quantity.")
		 	, sale_price= ".$salePrice.",sale_quantity=".$product_quantity." WHERE lot_id = " . $get_avail->lot_id);
      	return true;
		 }
}

	public function saleSearchList(Request $request) {

		$client = $request->client_id;
		$invoice = $request->invoice_id;
		$dateFrom = $request->dateSearchFldFrom;
		$dateTo = $request->dateSearchFldTo;
//print_r($request->all());exit;
		$dataArray = array();

		$query = $this->getQuery($request);

		$sales_products = DB::select($query);

		// self::print_me($query);

		// if ($request->client_id) {
		// 	$query .= "AND msp.client_id=".$request->client_id;
		// }

		$columns = Mxp_taxvat::select('name')->where('status', '=', '1')->get();

		$clients=DB::select("SELECT mc.acc_final_name,sc.head_sub_class_name,mc.chart_o_acc_head_id,cl.head_class_name FROM mxp_chart_of_acc_heads mc
JOIN mxp_acc_head_sub_classes sc ON(mc.mxp_acc_head_sub_classes_id=sc.mxp_acc_head_sub_classes_id)
JOIN mxp_acc_classes cl ON(cl.mxp_acc_classes_id=mc.mxp_acc_classes_id)
JOIN mxp_accounts_sub_heads sh ON(sh.accounts_sub_heads_id=mc.accounts_sub_heads_id)
JOIN mxp_accounts_heads hd ON(hd.accounts_heads_id=mc.accounts_heads_id)
WHERE cl.mxp_acc_classes_id='83' AND mc.mxp_acc_head_sub_classes_id='94' AND mc.group_id=".self::getGroupId()." AND mc.company_id=".self::getConpanyId()."");

		$invoice_codes = DB::select("select * from mxp_invoice where company_id = " . self::getConpanyId() . " AND com_group_id = " . self::getGroupId());

		$request->session()->put('fromDate', $request->dateSearchFldFrom);
		$request->session()->put('toDate', $request->dateSearchFldTo);
		$request->session()->put('client_id_for_select_field', $request->client_id);
		$request->session()->put('invoice_id_for_select_field', $request->invoice_id);
		$request->session()->put('stock_status', $request->stock_status);

		 //self::print_me($clients);exit;

		// return view('product.purchase.purchase_list', [
		// 	'columns' => $columns,
		// 	'sale_products' => $sale_products,
		// 	'clients' => $clients,
		// 	'invoice_codes' => $invoice_codes]);
		return view('product.sale.sale_list', ['columns' => $columns,'sales_products' => $sales_products, 'clients' => $clients, 'invoice_codes' => $invoice_codes]);
	}

	private function getQuery(Request $request) {
		$query = $this->getBaseQuery($request);

		if ($request->client_id) {
			$query .= " AND msp.client_id=" . $request->client_id;
		}

		if ($request->invoice_id) {
			$query .= " AND msp.invoice_id=" . $request->invoice_id;
		}

		if ($request->dateSearchFldFrom) {
			$query .= " AND msp.sale_date >= '" . $request->dateSearchFldFrom . "'";
		}

		if ($request->dateSearchFldTo) {
			$query .= " AND msp.sale_date <= '" . $request->dateSearchFldTo . "'";
		}

		if ($request->stock_status) {
			$query .= " AND msp.stock_status = '" . $request->stock_status . "'";
		}

		$query .= " GROUP BY mtvc.sale_purchase_id;";

		return $query;
	}

	private function getBaseQuery(Request $request) {

		return "SELECT
			mp.name as product_name,
			mpk.name as packet_name,
			mpk.quantity as packet_quantity,
			munit.name as unit_name,
			mpk.unit_quantity,
			mu.first_name as client_name,
			msp.quantity,
			msp.price,
			mi.invoice_code,
			mt.transport_name,
			msp.sale_date,
			GROUP_CONCAT(mtvc.calculate_amount) as new_vat_tax,
			GROUP_CONCAT(mtv.name) as vat_tax_name,
			GROUP_CONCAT(mtvc.vat_tax_id) as vat_tax_id
			from mxp_sale_products msp
			LEFT JOIN mxp_product mp ON mp.id = msp.product_id
			LEFT JOIN mxp_invoice mi ON mi.id = msp.invoice_id
			LEFT JOIN mxp_users mu ON mu.user_id = msp.client_id
			LEFT JOIN mxp_transports mt ON mt.invoice_id = msp.invoice_id
			LEFT JOIN mxp_packet mpk ON mpk.id = mp.packing_id
			LEFT JOIN mxp_unit munit ON munit.id = mpk.unit_id
			LEFT JOIN mxp_taxvat_cals mtvc ON mtvc.invoice_id = msp.invoice_id
			LEFT JOIN mxp_taxvats mtv ON mtvc.vat_tax_id=mtv.id
			WHERE msp.com_group_id=1
			AND msp.company_id=0
			AND msp.user_id=1
			AND msp.is_deleted=0";
	}

}
