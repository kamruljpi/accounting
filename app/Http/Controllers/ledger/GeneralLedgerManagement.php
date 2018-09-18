<?php

namespace App\Http\Controllers\ledger;

use App\Http\Controllers\API\AccountHeadApi;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\SubModuleInterface;
use App\Model\MxpJournalPosting;
use App\Model\MxpChartOfAccHead;
use Auth;
use Illuminate\Http\Request;
use DB;
use Validator;

class GeneralLedgerManagement extends Controller
{
	public function index(Request $request) 
	{
		$chart_of_accounts = json_decode(AccountHeadApi::chartOfAccount($request));

		return view('ledger.general.index',['chart_of_accounts'=>$chart_of_accounts]);
	}

	public function SearchAction(Request $request) 
	{
		$validator = $this->checkValidation($request);

		if (!$validator->fails())
		{
			$ledgers = DB::select('call get_ledgers_by_acc_head_id('.$request->ledger_chart_of_acc.' , "'.$request->ledgerDateSearchFldFrom.'" , "'.$request->ledgerDateSearchFldTo.'")');

			return $ledgers;
		}
		else 
			return -1;
	}

	private function checkValidation(Request $request) 
	{
		$validator = Validator::make($request->all(), [
            'ledger_chart_of_acc' 		=> 	'required',
            'ledgerDateSearchFldFrom' 	=> 	'required',
            'ledgerDateSearchFldTo' 	=> 	'required'
        ]);

        return $validator;
	}

	public function getGeneralJournalDetails(Request $request)
	{
		$journal 	= 	MxpJournalPosting::find($request->id);
		$ledger 	= 	DB::select('call get_ledger_details_journal_by_id('.$request->id.', '.$journal->account_head_id. ')');
//		$this->print_me($ledger);
		return view('layouts.pop_up_modal.ledgerDetailsViewModal',['ledger' => $ledger]);
	}
}
