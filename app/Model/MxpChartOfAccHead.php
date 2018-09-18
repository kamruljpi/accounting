<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MxpChartOfAccHead extends Model
{
	protected $table = "mxp_chart_of_acc_heads";
	protected $primaryKey = "chart_o_acc_head_id";


	protected $fillable = [
		'acc_final_name','mxp_acc_head_sub_classes_id','mxp_acc_classes_id','accounts_heads_id','accounts_sub_heads_id','company_id','group_id','user_id','is_deleted','is_active'
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
		return $this->hasOne('App\Model\MxpAccClass','mxp_acc_classes_id','mxp_acc_classes_id');
	}

	public function accountHeadSubClass()
	{
		return $this->hasOne('App\Model\MxpAccHeadSubClass','mxp_acc_head_sub_classes_id','mxp_acc_head_sub_classes_id');
	}
}










