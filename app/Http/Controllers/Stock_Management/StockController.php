<?php

namespace App\Http\Controllers\Stock_Management;

use App;
use App\Http\Controllers\Controller;
use App\Model\MxpProductPurchase;
use App\Model\MxpStock;
use App\Model\MxpStore;
use App\Model\MxpTaxvatCals;
use App\Model\MxpLcPurchase;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Validator;

class StockController extends Controller {
	public function indexStock($page = 1) 
	{
        $start              =   ($page-1)*$this->limitPerPage;

//        $totalData          =   MxpProductPurchase::where('company_id',self::getConpanyId())->where('com_group_id',self::getGroupId())->where('stock_status',0)->count();

        $lcproducts = DB::select('call get_all_lc_pro_in_stocks(' .self::getGroupId(). ',' .self::getConpanyId().')');

//		$products = DB::select('call get_all_stocks(' .self::getGroupId(). ',' .self::getConpanyId().','.$start.','.$this->limitPerPage.')');
		$products = DB::select('call get_all_stocks(' .self::getGroupId(). ',' .self::getConpanyId().')');

		$stock_states 		=  MxpStore::where('com_group_id', self::getGroupId())->where('company_id', self::getConpanyId())->where('user_id', self::getUserId())->where('is_deleted', 0)->get();

		return view('Stock_Management.stock.indexStock', [
			'products' 		=>  $products,
			'stock_states'  =>  $stock_states,
            'currentPage'   =>  $page, 
//            'totalData'     =>  $totalData,
            'lcproducts'     =>  $lcproducts,
            'limitPerPage'  =>  $this->limitPerPage]);
	}

	public function saveStockAction(Request $request) {
//	    $this->print_me($request->all());
		$validator = Validator::make($request->all(), [
			'location' => 'required',
		]);

		if ($validator->fails())
			return redirect()->back()->withInput($request->input())->withErrors($validator->messages());

		else 
		{
            $productPurchase = null;
		    if(isset($request->local_pro_id))
                $this->setLocalProductPurchase($request);

		    elseif ($request->lc_purchase_id)
                $this->setLCProductPurchase($request);
		}

        return redirect()->route('stock_view');
	}

	private function setLocalProductPurchase(Request $request)
    {
        MxpProductPurchase::where('id', $request->local_pro_id)->update(['stock_status' => 1]);
        $stock = null;

		if ($request->local_pro_id) {
			$stock = MxpProductPurchase::find($request->local_pro_id);
		} else {
			$stock = new MxpProductPurchase();
		}
        $this->setAvgPriceWithQunty($stock, $request->location);
	}

	private function setAvgPriceWithQunty($productPurchase, $location) 
	{
		$totalPrice = $this->getTotalPrice($productPurchase->id);

		$existProduct = MxpStock::where('product_id', $productPurchase->product_id)->get();

		if (count($existProduct) > 0) 
		{
			$avg_price_unit = ($totalPrice + ($existProduct[0]->available_quantity*$existProduct[0]->avg_price_unit))/($existProduct[0]->available_quantity+$productPurchase->quantity+$productPurchase->bonus);

			foreach ($existProduct as $key => $value) {
				MxpStock::where('product_id', $productPurchase->product_id)->update([
					'avg_price_unit' => $avg_price_unit,
					'available_quantity' => $value->available_quantity + $productPurchase->quantity + $productPurchase->bonus]);
			}
			$this->setStock($productPurchase, $existProduct[0]->available_quantity, $location, $avg_price_unit);
		} 
		else
			$this->setStock($productPurchase, 0, $location, $totalPrice/($productPurchase->quantity+$productPurchase->bonus));
	}

    private function getTotalPrice($id)
    {
        $vatTaxCal = MxpTaxvatCals::where('type', 'purchase')->where('sale_purchase_id', $id)->get();

        return $vatTaxCal[0]->total_amount_with_vat;
    }

    private function setStock(MxpProductPurchase $productPurchase, $avail_qnty, $location, $totalPrice)
    {
        $stock = new MxpStock();

        $stock->purchase_id 		= 	$productPurchase->id;
        $stock->product_id 			= 	$productPurchase->product_id;
        $stock->invoice_id			= 	$productPurchase->invoice_id;
        $stock->company_id 			= 	$productPurchase->company_id;
        $stock->com_group_id 		= 	$productPurchase->com_group_id;
        $stock->user_id 			= 	$productPurchase->user_id;
        $stock->quantity 			= 	$productPurchase->quantity + $productPurchase->bonus;
        $stock->available_quantity 	= 	$productPurchase->quantity + $productPurchase->bonus + $avail_qnty;
        $stock->avg_price_unit	 	= 	$totalPrice;
        $stock->store_id 			= 	$location;
        $stock->lot_id 				= 	self::getRandomGroupId();
        $stock->status 				= 	1;
        $stock->is_deleted 			= 	0;
        $stock->save();
    }

    private function setLCProductPurchase(Request $request)
    {
        MxpLcPurchase::where('lc_purchase_id', $request->lc_purchase_id)->update(['stock_status' => 1]);
        $stock = null;

        if ($request->lc_purchase_id) {
            $stock = MxpLcPurchase::find($request->lc_purchase_id);
        } else {
            $stock = new MxpProductPurchase();
        }
        $this->setAvgPriceWithQuntyForLc($stock, $request->location);
    }

    private function setAvgPriceWithQuntyForLc($productPurchase, $location)
    {
        $avg_price_unit = $productPurchase->total_amount_with_other_cost/$productPurchase->quantity;

        //$existProduct = MxpStock::where('product_id', $productPurchase->product_id)->get();

        /*if (count($existProduct) > 0)
        {
            foreach ($existProduct as $key => $value) {
                MxpStock::where('product_id', $productPurchase->product_id)->update([
                    'avg_price_unit' => $avg_price_unit,
                    'available_quantity' => $value->available_quantity + $productPurchase->quantity]);
            }
            $this->setStockLcProduct($productPurchase, $existProduct[0]->available_quantity, $location, $avg_price_unit);
        }
        else*/
            $this->setStockLcProduct($productPurchase, $productPurchase->quantity, $location, $avg_price_unit);
    }

    private function setStockLcProduct(MxpLcPurchase $productPurchase, $avail_qnty, $location, $totalPrice)
    {
        $stock = new MxpStock();

        $stock->purchase_id 		= 	$productPurchase->lc_purchase_id;
        $stock->product_id 			= 	$productPurchase->product_id;
        $stock->invoice_id			= 	$productPurchase->invoice_id;
        $stock->company_id 			= 	$productPurchase->company_id;
        $stock->com_group_id 		= 	$productPurchase->com_group_id;
        $stock->user_id 			= 	$productPurchase->user_id;
        $stock->quantity 			= 	$productPurchase->quantity + $productPurchase->bonus;
        $stock->available_quantity 	= 	$productPurchase->bonus + $avail_qnty;
        $stock->avg_price_unit	 	= 	$totalPrice;
        $stock->store_id 			= 	$location;
        $stock->lot_id 				= 	self::getRandomGroupId();
        $stock->status 				= 	1;
        $stock->is_deleted 			= 	0;
        $stock->save();
    }

	public function updateStocksForm($page = 1) 
	{
        $start              =   ($page-1)*$this->limitPerPage;

        $totalData          =   MxpStore::where('company_id',self::getConpanyId())->where('com_group_id',self::getGroupId())->count();

		$stocks = DB::select('call get_all_products_of_stock(' .self::getGroupId(). ',' .self::getConpanyId().','.$start.','.$this->limitPerPage. ')');

		// self::print_me($page);

		$stock_states = MxpStore::where('com_group_id', self::getGroupId())->where('company_id', self::getConpanyId())->where('user_id', self::getUserId())->where('is_deleted', 0)->get();

		return view('Stock_Management.stock.updateStocks', [
			'stocks' 		=> 	$stocks, 
			'stock_states'  => 	$stock_states,
            'currentPage'   =>  $page, 
            'totalData'     =>  $totalData, 
            'limitPerPage'  =>  $this->limitPerPage]);
	}

	public function updateStocksAction(Request $request) {
		MxpStock::where('id', $request->id)->update(['store_id' => $request->location]);

		return redirect()->route('update_stocks_view');
	}
}
