<?php

namespace App\Http\Controllers\ledger;

use App\Http\Controllers\Controller;
use App\Model\MxpClientCompany;
use App\Model\MxpJournalPosting;
use App\Model\MxpChartOfAccHead;
use App\Http\Controllers\ledger\GeneralLedgerManagement;
use Auth;
use Illuminate\Http\Request;
use DB;
use Validator;

class PartyLedgerManegement extends Controller
{
	public function index(Request $request) 
	{
		// $trialList = array();
		// $trailBal = $this->getTrialBalSheet(13);
		// self::print_me($trailBal);

//		$clients=DB::select("SELECT mc.acc_final_name,sc.head_sub_class_name,mc.chart_o_acc_head_id,cl.head_class_name FROM mxp_chart_of_acc_heads mc
//JOIN mxp_acc_head_sub_classes sc ON(mc.mxp_acc_head_sub_classes_id=sc.mxp_acc_head_sub_classes_id)
//JOIN mxp_acc_classes cl ON(cl.mxp_acc_clcounts_sub_heads_id=mc.accounts_sub_heads_id)
////JOIN mxp_accounts_heads hd ON(hd.accouasses_id=mc.mxp_acc_classes_id)
//JOIN mxp_accounts_sub_heads sh ON(sh.acnts_heads_id=mc.accounts_heads_id)
//WHERE cl.mxp_acc_classes_id='83' AND mc.mxp_acc_head_sub_classes_id='94' AND mc.group_id=".self::getGroupId()." AND mc.company_id=".self::getConpanyId()."");

        $clients = $this->getAccHeadParty();

		// $clients = MxpChartOfAccHead::get()->where('company_id', self::getConpanyId())->where('group_id', self::getGroupId())/*->where('mxp_acc_head_sub_classes_id', $this->subClaasForSale)*/;
		return view('ledger.party.index',['clients'=>$clients]);
	}

	private function getTrialBalSheet($id)
	{
		$chart_of_acc = MxpChartOfAccHead::where('accounts_heads_id',$id)->get();
		$cnt = 0;
		$trials = array();

		foreach ($chart_of_acc as $key => $value) 
		{
			$balance = DB::select('call get_ledgers_by_acc_head_id('.$value->chart_o_acc_head_id.' , "2018-05-1" , "2018-05-16")');
			$length = count($balance);
			if ($length > 0) 
			{
				array_push($trials, $balance[$length-1]);
			}
		}

		// return $chart_of_acc;
		return $trials;
	}

	public function SearchAction(Request $request) 
	{
		$validator = $this->checkValidation($request);

		if (!$validator->fails())
		{
			
			$ledgers = DB::select('call get_ledgers_by_client_id('.$request->ledger_client_id.' , "'.$request->ledgerDateSearchFldFrom.'" , "'.$request->ledgerDateSearchFldTo.'")');

			return $ledgers;
		}
		else 
			return -1;
	}

	private function checkValidation(Request $request) 
	{
		$validator = Validator::make($request->all(), [
            'ledger_client_id' 			=> 	'required',
            'ledgerDateSearchFldFrom' 	=> 	'required',
            'ledgerDateSearchFldTo' 	=> 	'required'
        ]);

        return $validator;
	}

	public function getPartyJournalDetails(Request $request)
	{
		$ledger = DB::select('call get_ledger_details_journal_by_id('.$request->id.', -1)');

		return view('layouts.pop_up_modal.ledgerDetailsViewModal',['ledger' => $ledger]);
	}
}
