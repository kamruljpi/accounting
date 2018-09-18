<?php

namespace App\Http\Controllers\product;

use App;
use App\Http\Controllers\Controller;
use App\MxpProduct;
use App\MxpProductGroup;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Validator;

class ProductEntryController extends Controller 
{
	public function index($page = 1) 
	{
        $start              =   ($page-1)*$this->limitPerPage;

        $totalData          =   MxpProduct::where('company_id',self::getConpanyId())->where('com_group_id',self::getGroupId())->where('is_active',1)->where('is_deleted',0)->count();

		$products = DB::select('call get_all_products(' . self::getGroupId() . ',' . self::getConpanyId().','.$start.','.$this->limitPerPage . ')');

		return view('product.product_entry.index', [
			'products' 		=> 	$products,
            'currentPage'   =>  $page, 
            'totalData'     =>  $totalData, 
            'limitPerPage'  =>  $this->limitPerPage]);
	}

	public function createProductForm() {

		$productGroups = MxpProductGroup::get()->where('com_group_id', self::getGroupId())->where('company_id', self::getConpanyId())->where('is_deleted', 0)->where('is_active', 1);

		$packets = DB::select('call get_all_packets(' . self::getConpanyId() . ',' . self::getGroupId() . ')');

		return view('product.product_entry.add_product', ['productGroups' => $productGroups, 'packets' => $packets]);
	}

	public function createProductAction(Request $request) {

		$name = $request->p_name;
		$code = $request->p_code;
		$packing_ids = $request->packet_ids;
		$product_group_id = $request->p_group;
		$is_active = $request->is_active;
		$is_deleted = 0;
		$ran_group_id = self::getRandomGroupId();

		if (count($packing_ids) == 0) {
			return redirect()->back()->withInput($request->input())->withErrors('Select at least one packet detais');
		} else {
			$packing_id_comma = implode('-', $packing_ids);
			$packing_id_comb = ltrim($packing_id_comma);

			foreach ($packing_ids as $packet_id) {
				$validator = Validator::make($request->all(), [
					'p_name' => 'required|unique:mxp_product,name,null,null,company_id,' . self::getConpanyId() . ',com_group_id,' . self::getGroupId() . ',user_id,' . self::getUserId() . ',packing_id,' . $packet_id . ',product_group_id,' . $product_group_id . ',is_deleted,0',
					'p_code' => 'required',
				]);

				// $validator = Validator::make($request->all(), [
				//     'p_name' => 'required',
				//     'packet_ids'=>'required|unique_check_more_field_comb:mxp_product,packing_id,company_id,'.self::getConpanyId().',com_group_id,'.self::getGroupId().',user_id,'.self::getUserId().',name,'.$name.',product_group_id,'.$product_group_id.',group_id,'.$ran_group_id.',comb_id,'.$packing_id_comb.',is_deleted,0',

				// ]);

				if ($validator->fails()) {
					return redirect()->back()->withInput($request->input())->withErrors($validator->messages());
				} else {
					// foreach ($packing_ids as $packet_id)
					// {
					$product = new MxpProduct();
					$product->com_group_id = self::getGroupId();
					$product->company_id = self::getConpanyId();
					$product->user_id = self::getUserId();
					$product->name = $name;
					$product->product_code = $packet_id . '_' . $ran_group_id . '_' . $code;
					$product->packing_id = $packet_id;
					$product->product_group_id = $product_group_id;
					$product->is_active = $is_active;
					$product->is_deleted = $is_deleted;
					$product->group_id = $ran_group_id;
					$product->comb_id = $packing_id_comb;
					$product->save();
					// }
				}
			}
		}

		return redirect()->route('entry_product_list_view');
	}

	public function editProductForm(Request $request) {

		$productGroups = MxpProductGroup::get()->where('com_group_id', self::getGroupId())->where('company_id', self::getConpanyId())->where('is_deleted', 0)->where('is_active', 1);

		$packets = DB::select('call get_all_packets(' . self::getConpanyId() . ',' . self::getGroupId() . ')');

		$product = MxpProduct::where('group_id', $request->group_id)->where('is_deleted', 0)->get();

		$productPaketList = [];
		foreach ($product as $prod) {
			array_push($productPaketList, $prod->packing_id);
		}

		return view('product.product_entry.edit_product_entry', ['product' => $product, 'productPaketList' => $productPaketList, 'packets' => $packets, 'productGroups' => $productGroups]);
	}

	public function editProductAction(Request $request) {
		$name = $request->name;
		$code = $request->code;
		$user_packing_ids = $request->packet_ids;
		$product_group_id = $request->p_group;
		$is_active = $request->isActive;
		$is_deleted = 0;
		$ran_group_id = $request->group_id;
		$id = $request->id;

		// $validator = Validator::make($request->all(), [
		// 'name'=>'required',
		// 'packet_ids'=>'required|unique_check_more_field_comb:mxp_product,packing_id,company_id,'.self::getConpanyId().',com_group_id,'.self::getGroupId().',user_id,'.self::getUserId().',name,'.$name.',product_group_id,'.$product_group_id.',group_id,'.$ran_group_id.',comb_id,'.$packing_id_comb.',is_deleted,0'
		// ]);

		// MxpProduct::where('id',$id)->update(['product_code' => $packet_id.'_'.$ran_group_id.'_'.$code]);

		$temp = 0;

		if (count($user_packing_ids) == 0) {
			return redirect()->back()->withInput($request->input())->withErrors('Select at least one packet detais');
		} else {
			$packing_id_comma = implode('-', $user_packing_ids);
			$packing_id_comb = ltrim($packing_id_comma);

			foreach ($user_packing_ids as $packet_id) {
				$validator = Validator::make($request->all(), [
					'name' => 'required|unique:mxp_product,name,' . $id . ',id,company_id,' . self::getConpanyId() . ',com_group_id,' . self::getGroupId() . ',user_id,' . self::getUserId() . ',packing_id,' . $packet_id . ',product_group_id,' . $product_group_id . ',product_code,' . $packet_id . '_' . $ran_group_id . '_' . $code . ',is_deleted,0',
					'code' => 'required',
				]);

				if ($validator->fails()) {
					$temp = -1;
					return redirect()->back()->withInput($request->input())->withErrors($validator->messages());
				}
			}
		}

		if ($temp == 0) {
			$products = MxpProduct::where('group_id', $ran_group_id)->get();

			// $products = DB::select('select id, packing_id from mxp_product where group_id = '.$ran_group_id);

			$database_packets = [];
			$ids = [];

			foreach ($products as $product) {
				MxpProduct::where('id', $product->id)->update(['name' => $name, 'product_group_id' => $product_group_id, 'is_active' => $is_active, 'product_code' => $product->packing_id . '_' . $product->group_id . '_' . $code]);

				// if($product->is_deleted == 0)
				// {
				array_push($database_packets, $product->packing_id);
				array_push($ids, $product->id);
				// }
			}

			// self::print($database_packets);

			foreach ($user_packing_ids as $user_packing_id) {
				if (!in_array($user_packing_id, $database_packets)) {

					$product = new MxpProduct();
					$product->com_group_id = self::getGroupId();
					$product->company_id = self::getConpanyId();
					$product->user_id = self::getUserId();
					$product->name = $name;
					$product->packing_id = $user_packing_id;
					$product->product_group_id = $product_group_id;
					$product->is_active = $is_active;
					$product->is_deleted = $is_deleted;
					$product->group_id = $ran_group_id;
					$product->comb_id = $packing_id_comb;
					$product->save();
					// echo "get one";
					// die();
				} else {
					MxpProduct::where('packing_id', $user_packing_id)->where('group_id', $ran_group_id)->update(['is_deleted' => 0]);
				}
			}

			foreach ($database_packets as $key => $value) {
				if (!in_array($value, $user_packing_ids)) {
					MxpProduct::where('id', $ids[$key])->update(['is_deleted' => 1]);
				}
			}
		}

/*
$validator = Validator::make($request->all(), [
'name'=>'required',
'packet_ids'=>'required|unique_check_more_field_comb:mxp_product,packing_id,company_id,'.self::getConpanyId().',com_group_id,'.self::getGroupId().',user_id,'.self::getUserId().',name,'.$name.',product_group_id,'.$product_group_id.',group_id,'.$ran_group_id.',comb_id,'.$packing_id_comb.',is_deleted,0'
]);

if ($validator->fails())
return redirect()->back()->withInput($request->input())->withErrors($validator->messages());
else
{
foreach ($packing_ids as $packet_id)
{
$product                    =   new MxpProduct();
$product->com_group_id      =   self::getGroupId();
$product->company_id        =   self::getConpanyId();
$product->user_id           =   self::getUserId();
$product->name              =   $name ;
$product->packing_id        =   $packet_id;
$product->product_group_id  =   $product_group_id;
$product->is_active         =   $is_active ;
$product->is_deleted        =   $is_deleted;
$product->group_id          =   $ran_group_id;
$product->comb_id           =   $packing_id_comb;
$product->save();
}
}
 */
		return redirect()->route('entry_product_list_view');
	}

	public function deleteProduct(Request $request) {
		$delete = MxpProduct::where('id', $request->id)->update(['is_deleted' => 1]);

		return redirect()->route('entry_product_list_view');
	}
}
