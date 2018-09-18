<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MxpTaxvatCals extends Model {
	protected $table = "mxp_taxvat_cals";
	protected $fillable = ['invoice_id', 'product_id', 'vat_tax_id', 'sale_purchase_id', 'type', 'company_id', 'group_id', 'user_id', 'total_amount', 'calculate_amount','percent', 'total_amount_with_vat','price_per_unit'];
}