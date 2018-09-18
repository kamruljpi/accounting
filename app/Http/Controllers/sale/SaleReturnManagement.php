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

class SalereturnManagement extends Controller {

	public function SalereturnView(Request $request){

	$get_oreder_no=DB::select('call get_order_noBY_type("sale",' . self::getGroupId() . ',' . self::getConpanyId() . ')');
	
		return view('product.sale_return.sale_return',[
			'get_oreder_no'  =>  $get_oreder_no,
			'slreturn'=>"",
			'chartOfAccs'=>""]);
	}
	
	public function getSalesResult(Request $request){
	$get_oreder_no=DB::select('call get_order_noBY_type("sale",' . self::getGroupId() . ',' . self::getConpanyId() . ')');
$chartOfAccs = MxpChartOfAccHead::get()->where('company_id', self::getConpanyId())->where('group_id', self::getGroupId());
		$slreturn = DB::table('mxp_invoice as mi')->where(['mi.order_no'=>$request['order_no']])
		->leftjoin('mxp_sale_products as msp', 'mi.id', '=', 'msp.invoice_id')
		->leftjoin('mxp_taxvat_cals as mtc', 'msp.invoice_id', '=', 'mtc.invoice_id' )
		->join('mxp_product as mpr', 'msp.product_id','=', 'mpr.id')
		->leftjoin('mxp_packet as mp', 'mpr.packing_id','=', 'mp.id')
		->leftjoin('mxp_unit as mu', 'mp.unit_id','=', 'mu.id')
		->leftjoin('mxp_product_group as mpg', 'mpr.product_group_id','=', 'mpg.id')
		->leftjoin('mxp_transports as mt', 'mtc.invoice_id','=', 'mt.invoice_id')
		->join('mxp_stock as ms', 'msp.product_id','=', 'ms.product_id')
		->leftjoin('mxp_chart_of_acc_heads as mcah', 'msp.client_id','=', 'mcah.chart_o_acc_head_id')
		->SELECT('mpr.name as p_name','mu.name as u_name','mt.transport_number',
			'mt.transport_name','mt.transport_date','msp.sale_date','mp.name as pkt_name',
			'mp.unit_quantity','mi.invoice_code','mi.order_no','mtc.percent','mtc.price_per_unit',
			 'mcah.acc_final_name','msp.quantity as slquantity','msp.price','msp.due_ammount','msp.bonus',
			  'msp.total_amount_w_vat as total_amount','msp.invoice_id','mi.client_id',
			  'msp.product_id','msp.company_id','msp.com_group_id','msp.bonus','ms.lot_id','msp.product_id')
		->groupBy('msp.id')->get();
//echo '<pre>';print_r($slreturn);exit;
		return view('product.sale_return.sale_return',[
			'get_oreder_no'  =>  $get_oreder_no,
			'slreturn'=>$slreturn,
			'chartOfAccs'=>$chartOfAccs
			]);

	}


	public function storeSaleReturn(Request $request){

		// $validator = Validator::make($request->all(), [
		// 	'invoice_code.*' => 'required',
		// 	'company_id' => 'required',
		// 	'com_group_id' => 'required',
		// 	'product_id.*' => 'required',
		// 	'client_id.*' => 'required',
		// 	'quantity.*' => 'required',
		// 	'sales_price.*' => 'required',
		// 	'transport_name.*' => 'required',
		// 	'transport_no.*' => 'required',
		// 	'transport_date.*' => 'required',
		// 	'sales_date.*' => 'required',
		//     'chartOfAcc.*'=> 'required',	
		// ]);
		// if ($validator->fails()) {
		// 	return redirect()->back()->withInput($request->input())->withErrors($validator->messages());
		// }
		/*$getAll=DB::select('select sp.*,st. from mxp_sale_products sp join mxp_stock st on()');*/
$result=array();
$data=array();
foreach ($request->all() as $key => $value) {
	if($key!='_token'){
		foreach ($value as  $key1 => $value1) {
			//echo $key;
			
			$result[$key1][$key]=$value1;
	 }
	}
	
}
foreach ($result as $key => $value) {
	if(isset($value['lot_id']) && $value['lot_id']!=""){
      $data[$key]=(object)$value;
//echo '<pre>';print_r($value['chart_of_acc']);
	
	}
	
}

for ($i=0; $i <count($data) ; $i++) { 
	$this->setJournal($data[$i]);
}

	return redirect('product/sale/sales_return');
}
public function setJournal($data){
/*			
echo '<pre>';print_r($data->product_id);*/		 
		$inventory = MxpChartOfAccHead::find($this->inventoryOrStock);
		$costOfGood = MxpChartOfAccHead::find($this->cost_of_good_sold);
        $chartOfAccData = MxpChartOfAccHead::find($data->chart_of_acc);
        $stocks = MxpStock::where('product_id',$data->product_id)->get();
        $av_price=$stocks[0]->avg_price_unit*$data->slquantity;
		$randomGroupId = self::getRandomGroupId();
		$journal = new LCPurchaseManagement();

		if ($data->due_ammount>0) 
			{

			$journal->saveToJurnal(
			1,
			 $this->companyLoan,
			'party('.$data->jurnalParticular.')',
			'Sale return', 
			$data->due_ammount,
			0,
			$randomGroupId,
			date('y-m-d:h m s'),
			$data->client_id,
			$data->product_id,0,0,
			$data->client_id);

			}

		$journal->saveToJurnal(1,
			$data->client_id,
			$data->jurnalParticular,
			'Sale return', 
			$data->total_ammount,
			0, 
			$randomGroupId,
			date('y-m-d:h m s'),
			$data->client_id,
			$data->product_id,0,0,
			$data->client_id);

		$journal->saveToJurnal(1,
			$data->chart_of_acc,
			$chartOfAccData->acc_final_name,
			'Sale return', 
			0,
			$data->total_ammount,
			$randomGroupId,
			date('y-m-d:h m s'),
			$data->client_id,
			$data->product_id,0,0,
			$data->client_id);

		$journal->saveToJurnal($this->transaction_type_id,
			$this->chartOfSale,
			'Sale',
			'Sale return',
			$data->total_ammount, 
			0,
			$randomGroupId,
			date('y-m-d:h m s'),
			$data->client_id,
			$data->product_id,0,0,
			$data->client_id);

		$journal->saveToJurnal(1,
			$data->client_id,
			'party('.$data->jurnalParticular.')',
			'Sale return',
			0,
			$data->total_ammount, 
			$randomGroupId,
			date('y-m-d:h m s'),
			$data->client_id,
			$data->product_id,0,0,
			$data->client_id);
//
		$journal->saveToJurnal(1, 
			$this->cost_of_good_sold, 
			$costOfGood->acc_final_name.'/'.$data->product_name,
			'Sale return',
			 0,
			$av_price-$data->due_ammount,
			$randomGroupId,
			date('y-m-d:h m s'),
			$data->client_id,
			$data->product_id,0,0,
			$data->client_id);

		$journal->saveToJurnal(1, 
			$this->inventoryOrStock, 
			$inventory->acc_final_name.'/'.$data->product_name,
			'Sale return',  
			$av_price-$data->due_ammount,
			0,
			$randomGroupId,
			date('y-m-d:h m s'),
			$data->client_id,
			$data->product_id,0,0,
			$data->client_id);

	}

}
