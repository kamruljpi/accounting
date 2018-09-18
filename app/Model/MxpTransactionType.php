<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MxpTransactionType extends Model
{
	protected $table = "mxp_transaction_type";
	protected $primaryKey = "transaction_type_id";


	protected $fillable = [
		'transaction_type_name', 'company_id', 'group_id', 'user_id', 'is_deleted', 'is_active'
	];
}