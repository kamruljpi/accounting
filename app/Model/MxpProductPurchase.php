<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MxpProductPurchase extends Model {
	protected $table = "mxp_product_purchase";

	protected $fillable = ['product_id', 'invoice_id', 'client_id', 'com_group_id', 'company_id', 'user_id', 'quantity', 'price', 'stock_status', 'is_deleted', 'is_active','purchase_date','jouranl_posting_rand_grp_id','due_amount','account_head_id'];

	public function journalPosting()
	{
		return $this->hasOne('App\Model\MxpChartOfAccHead','posting_group_id','jouranl_posting_rand_grp_id');
	}

	public function product()
	{
		return $this->hasOne('App\MxpProduct','id','product_id');
	}

public function invoice()
	{
		return $this->hasOne('App\Model\MxpInvoice','id','invoice_id');
	}
}
