<?php

namespace App\Http\Controllers\API;

use App;
use App\Http\Controllers\Controller;
use App\Model\MxpAccClass;
use App\Model\MxpAccHeadSubClass;
use App\Model\MxpAccountsSubHead;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AccountHeadApi extends Controller {

	public function getAccountsSubHead(Request $request = null, $accounts_heads_id = null)
	{
        $accounts_sub_heads = MxpAccountsSubHead::get()->where('accounts_heads_id', self::getIdByRequestOrDefault($request, $accounts_heads_id))->where('is_deleted',0)->where('is_active',1);

        return json_encode($accounts_sub_heads);
	}

	public function getAccClass(Request $request = null, $acc_sub_head_id = null) {
		$acc_class = MxpAccClass::get()->where('accounts_sub_heads_id', self::getIdByRequestOrDefault($request, $acc_sub_head_id))->where('is_deleted', 0)->where('is_active', 1);

		return json_encode($acc_class);
	}

	public function getAccSubClass(Request $request = null, $acc_class_id = null) {
		$acc_sub_class = MxpAccHeadSubClass::get()->where('mxp_acc_classes_id', self::getIdByRequestOrDefault($request, $acc_class_id))->where('is_deleted', 0)->where('is_active', 1);

		return json_encode($acc_sub_class);
	}
	
	public function getAccSubClassReverse(Request $request)
	{
		// Please don't delete this, This is important for nabodip////start
	       $acc_sub_class    = 	MxpAccClass::find($request->acc_sub_class_id);
	       dd($acc_sub_class->accountHead);
	       //end
	       return json_encode($acc_sub_class);
	}

	public static function chartOfAccount(
		Request $request, $company_id = null,
		$group_id = null, $userId = null,
		$column_1 = "", $column_1_val = "",
		$column_2 = "", $column_2_val = "",
		$column_3 = "", $column_3_val = "") {

		$chartOfAccFinalName = DB::table('mxp_chart_of_acc_heads')
			->select('mxp_chart_of_acc_heads.*')
			->get()
			->where('company_id', (new self)->issetValue($company_id, $request->session()->get('company_id')))
			->where('group_id', (new self)->issetValue($group_id, $request->session()->get('group_id')))
			->where($column_1, $column_1_val)
			->where($column_2, $column_2_val)
			->where($column_3, $column_3_val)
			->where('is_deleted', 0);

		return json_encode($chartOfAccFinalName);
	}
}
