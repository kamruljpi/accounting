<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MxpAccountsHead extends Model
{
	protected $table = "mxp_accounts_heads";
	protected $primaryKey = "accounts_heads_id";


	protected $fillable = [
		'head_name_type', 'account_code', 'company_id', 'group_id', 'user_id', 'is_deleted', 'is_active'
	];
}
