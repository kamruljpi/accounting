<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Model\MxpJournalPosting;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use DB;

class GeneralData extends Controller {

	public function getCompanyUser(Request $request, $company_id = null, $userType = null, $group_id = null) {

		$companyUser = DB::table('mxp_users')
			->select('mxp_users.is_active as active_user', 'mxp_users.*', 'mxp_role.name')
			->leftjoin('mxp_role', 'mxp_role.id', '=', 'mxp_users.user_role_id')
			->get()
			->where('user_role_id', $this->issetValue($company_id, $request->comId))
			->where('type', $this->issetValue($userType, 'company_user'))
			->where('group_id', $this->issetValue($group_id, $request->session()->get('group_id')));

		return json_encode($companyUser);
	}

	public function getLastVoucherId() {

		$getId = MxpJournalPosting::max('journal_posting_id');

		return $getId;
	}

	public function getOrderNo() {

		$today = date("Ymd");
		$rand = strtoupper(substr(uniqid(sha1(time())),0,4));
		$unique = $today . $rand;

		return $unique;
	}

	public function getProductById($id)
	{
		$product = DB::select('call get_productDetails_by_id('.$id.')');
		return $product[0]->product_details;
	}

    public function getRoleName(Request $request, $company_id = null){
        $selectCompany = DB::table('mxp_role')
            ->where('company_id', $this->issetValue($company_id, $request->companyId))
            ->get();
        return json_encode($selectCompany);
    }

}
