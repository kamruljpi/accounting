<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MxpAccHeadSubClass extends Model
{
	protected $table = "mxp_acc_head_sub_classes";
	protected $primaryKey = "mxp_acc_head_sub_classes_id";


	protected $fillable = [
		'head_sub_class_name', 'mxp_acc_classes_id', 'accounts_heads_id', 'accounts_sub_heads_id', 'company_id', 'group_id', 'user_id', 'is_deleted', 'is_active'
	];

	public function accountHead()
	{
		return $this->hasOne('App\Model\MxpAccountsHead','accounts_heads_id','accounts_heads_id');
	}

	public function accountSubHead()
	{
		return $this->hasOne('App\Model\MxpAccountsSubHead','accounts_sub_heads_id','accounts_sub_heads_id');
	}

	public function accountClass()
	{
		return $this->hasMany('App\Model\MxpAccClass','mxp_acc_classes_id','mxp_acc_classes_id');
	}
}
