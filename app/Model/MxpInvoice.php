<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MxpInvoice extends Model {
	protected $table = "mxp_invoice";
	protected $fillable = ['invoice_code','client_id', 'company_id', 'com_group_id', 'type', 'user_id', 'is_deleted'];
}
