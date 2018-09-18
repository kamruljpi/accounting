<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use DB;

class Controller extends BaseController {
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $limitPerPage = 20;
    public $inventoryOrStock = 93;
    public $lcBankAcc = 53;
    public $lcBankLoanAcc = 52;
    public $companyLoan = 98;
    public $chartOfAccBank = 7;
    public $transaction_type_id = 4;
    public $ledger_client_id = 0;
    public $cost_of_good_sold = 97;

    public $subClaasForSale = 47;
    public $chartOfSale = 99;

    public $cash_in_hand=87;

    public $vat_id = 5;

	public function getRandomGroupId() {
		return strtotime(date('Y-m-d h:m:s')) % 1000000000;
	}

	protected function object_p($value) {
		echo '<pre>';
		print_r($value);
		die();
	}
	protected function print_me($value) {
		echo '<pre>';
		print_r($value);
		die();
	}

	protected function issetValue($container, $value) {
		if (empty($container)) {
			$container = $value;
		}

		return $container;
	}

	protected function getIdByRequestOrDefault($request, $value) {
		if ($request != null) {
			$value = $request->id;
		}

		return $value;
	}

	protected function getGroupId()
	{
		return session()->get('group_id');
	}

	protected function getConpanyId()
	{
		return session()->get('company_id')|0;
	}

	protected function getUserId()
	{
		return session()->get('user_id');
	}

	protected function getAccHeadClientForPur()
    {
        $clients=DB::select("SELECT mc.acc_final_name,sc.head_sub_class_name,mc.chart_o_acc_head_id,cl.head_class_name FROM mxp_chart_of_acc_heads mc
JOIN mxp_acc_head_sub_classes sc ON(mc.mxp_acc_head_sub_classes_id=sc.mxp_acc_head_sub_classes_id)
JOIN mxp_acc_classes cl ON(cl.mxp_acc_classes_id=mc.mxp_acc_classes_id)
JOIN mxp_accounts_sub_heads sh ON(sh.accounts_sub_heads_id=mc.accounts_sub_heads_id)
JOIN mxp_accounts_heads hd ON(hd.accounts_heads_id=mc.accounts_heads_id)
WHERE cl.mxp_acc_classes_id='83' AND mc.mxp_acc_head_sub_classes_id='95' AND mc.group_id=".self::getGroupId()." AND mc.company_id=".self::getConpanyId()."");

        return $clients;
    }
	protected function getAccHeadClientForSale()
    {
        $clients=DB::select("SELECT mc.acc_final_name,sc.head_sub_class_name,mc.chart_o_acc_head_id,cl.head_class_name FROM mxp_chart_of_acc_heads mc
JOIN mxp_acc_head_sub_classes sc ON(mc.mxp_acc_head_sub_classes_id=sc.mxp_acc_head_sub_classes_id)
JOIN mxp_acc_classes cl ON(cl.mxp_acc_classes_id=mc.mxp_acc_classes_id)
JOIN mxp_accounts_sub_heads sh ON(sh.accounts_sub_heads_id=mc.accounts_sub_heads_id)
JOIN mxp_accounts_heads hd ON(hd.accounts_heads_id=mc.accounts_heads_id)
WHERE cl.mxp_acc_classes_id='83' AND mc.mxp_acc_head_sub_classes_id='94' AND mc.group_id=".self::getGroupId()." AND mc.company_id=".self::getConpanyId()."");

        return $clients;
    }
	protected function getAccHeadParty()
    {
        $clients=DB::select("SELECT mc.acc_final_name,sc.head_sub_class_name,mc.chart_o_acc_head_id,cl.head_class_name FROM mxp_chart_of_acc_heads mc
JOIN mxp_acc_head_sub_classes sc ON(mc.mxp_acc_head_sub_classes_id=sc.mxp_acc_head_sub_classes_id)
JOIN mxp_acc_classes cl ON(cl.mxp_acc_classes_id=mc.mxp_acc_classes_id)
JOIN mxp_accounts_sub_heads sh ON(sh.accounts_sub_heads_id=mc.accounts_sub_heads_id)
JOIN mxp_accounts_heads hd ON(hd.accounts_heads_id=mc.accounts_heads_id)
WHERE cl.mxp_acc_classes_id='83' AND mc.mxp_acc_classes_id='83' AND mc.group_id=".self::getGroupId()." AND mc.company_id=".self::getConpanyId()."");

        return $clients;
    }
}


// select mjp.particular,
// mjp.transaction_amount_debit,
// mjp.transaction_amount_credit
// from mxp_journal_posting mjp
// join mxp_chart_of_acc_heads mcah on(mjp.account_head_id = mcah.chart_o_acc_head_id)
// where mcah.accounts_heads_id = 13;


// Error Code: 1054. Unknown column 'ledger.account_head_id' in 'where clause'
// Error Code: 1054. Unknown column 'acc_head.head_id' in 'where clause'

// SELECT ledger.openingbalance,
// ledger.journal_date

// FROM
// (
// select mcah.chart_o_acc_head_id as head_id from mxp_chart_of_acc_heads mcah
// where mcah.accounts_heads_id = 13
// )acc_head
// JOIN
// (
// SELECT x.journal_posting_id,
// x.journal_date,
// x.particular,
// x.account_head_id,
// mca.acc_final_name as ledgerName,
// x.transaction_amount_debit as debit, 
// x.transaction_amount_credit as credit, 
// SUM(y.bal) openingbalance
    
// FROM
//  ( 
//    SELECT *,transaction_amount_debit-transaction_amount_credit as bal FROM mxp_journal_posting  WHERE account_head_id=acc_head.head_id AND journal_date >= '2018-05-1' and journal_date <= '2018-05-16'
//  ) x

// JOIN
//  ( 
//    SELECT *,transaction_amount_debit-transaction_amount_credit as bal FROM mxp_journal_posting  WHERE account_head_id=acc_head.head_id
//  ) y
     
//    ON y.journal_posting_id <= x.journal_posting_id
   
// JOIN mxp_chart_of_acc_heads mca on(mca.chart_o_acc_head_id = x.account_head_id)
    
// GROUP BY x.journal_posting_id
// ORDER BY x.journal_posting_id ASC
// )ledger
// ON acc_head.head_id = ledger.account_head_id