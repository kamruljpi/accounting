<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MxpLcPurchase extends Model {
	protected $table = "mxp_lc_purchases";
	protected $primaryKey = "lc_purchase_id";


	protected $fillable = [
		'lc_no', 'lc_type', 'bank_swift_charge', 'vat', 'bank_commission', 'apk_form_charge', 'total_bank_charges', 'lc_margin', 'lc_amount_usd', 'lc_amount_taka', 'due_payment', 'others_cost', 'total_amount_with_other_cost', 'total_amount_without_other_cost', 'total_paid', 'dollar_rate', 'product_id', 'invoice_id', 'client_id', 'com_group_id', 'company_id', 'user_id', 'quantity', 'price', 'bonus', 'stock_status', 'purchase_date', 'is_deleted', 'lc_status', 'bank_name', 'country_orign', 'lc_margin_percent','account_head_id','jouranl_posting_rand_grp_id','lc_loan_account_head_id'];

	public function journalPosting()
	{
		return $this->hasOne('App\Model\MxpChartOfAccHead','jouranl_posting_rand_grp_id','posting_group_id');
	}
}
