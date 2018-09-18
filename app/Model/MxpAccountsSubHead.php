<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MxpAccountsSubHead extends Model
{
	protected $table = "mxp_accounts_sub_heads";
	protected $primaryKey = "accounts_sub_heads_id";


	protected $fillable = [
		'accounts_heads_id', 'sub_head', 'company_id', 'group_id', 'user_id', 'is_deleted', 'is_active'
	];
}
