<?php

namespace App\Http\Controllers\trailbalance;

use App\Http\Controllers\API\AccountHeadApi;
use App\Http\Controllers\Message\StatusMessage;
use App\Http\Controllers\Controller;
use App\MxpCompany;
use Auth;
use Illuminate\Http\Request;
use DB;
use Validator;

class TrailbalanceController extends Controller 
{
	public function listView()
    {
//     	$trialb = DB::select('SELECT ac.acc_final_name,jp.account_head_id,jp.transaction_amount_debit,
// jp.transaction_amount_credit,sub.head_sub_class_name,cls.head_class_name,
// sd.sub_head,ad.head_name_type,sum(jp.transaction_amount_debit-jp.transaction_amount_credit) as total,
// ac.accounts_heads_id,ac.accounts_sub_heads_id
// FROM mxp_journal_posting jp
// JOIN mxp_chart_of_acc_heads ac ON(jp.account_head_id=ac.chart_o_acc_head_id)
// JOIN mxp_acc_head_sub_classes sub ON(sub.mxp_acc_head_sub_classes_id=ac.mxp_acc_head_sub_classes_id)
// JOIN mxp_acc_classes cls ON(cls.mxp_acc_classes_id=ac.mxp_acc_classes_id)
// JOIN mxp_accounts_sub_heads sd ON(sd.accounts_sub_heads_id=ac.accounts_sub_heads_id)
// JOIN mxp_accounts_heads ad ON(ad.accounts_heads_id=ac.accounts_heads_id)
// group by jp.account_head_id');
// $reslt = array();
//         foreach ($trialb as $key => $value) {
//           // if(isset($reslt[$value->accounts_heads_id])){
//           //   $reslt[$value->accounts_heads_id]['head_name_type'] = $reslt[$value->accounts_heads_id]['head_name_type'];
//           // }else{
//           //   $reslt[$value->accounts_heads_id]['head_name_type'] = $value->head_name_type;
//           // }
           
//            /* $reslt[$value->accounts_heads_id]['head_class_name'] = $value->head_class_name;
//             $reslt[$value->accounts_heads_id]['head_sub_class_name'] = $value->head_sub_class_name;*/
//             // $reslt[$value->accounts_heads_id][$value->sub_head][] = $value;
//             // $reslt[][][][][][] = $value;

//             $reslt['index'][] = $value->accounts_heads_id;
//             $reslt['index'][] = $value->accounts_sub_heads_id;
//             $reslt['index'][] = $value->head_class_name;
//             $reslt['index'][] = $value->head_sub_class_name;
//             $reslt['index'][] = $value->acc_final_name;

//             $reslt['value'][$value->accounts_heads_id][$value->accounts_sub_heads_id][$value->head_class_name][$value->head_sub_class_name][$value->acc_final_name] = $value;
        
//         }
//         echo '<pre>';print_r($reslt);
//         die();
    	return view('trailbalance/trailbalance_list');
    }

	public function trialBalanceList(Request $request)
    {
		$validator = $this->checkValidation($request);

		if (!$validator->fails())
		{
			// $result = DB::select('call get_trial_balances("2018-05-01" , "2018-05-18");');
			$result = DB::select('call get_trial_balances("'.$request->TrailbalanceDateSearchFldFrom.'","'.$request->TrailbalanceDateSearchFldTo.'")');

			$trailList = array();

			foreach ($result as $key => $value) 
			{
	        	// if(isset($trailList[$value->accounts_heads_id]))
	        	// {
	         //    	$trailList[$value->accounts_heads_id]['head_name_type'] = $trailList[$value->accounts_heads_id]['head_name_type'];
	         //  	}
	         //  	else
	         //  	{
	         //    	$trailList[$value->accounts_heads_id]['head_name_type'] = $value->head_name_type;
	         //  	}
	           
	           /* $trailList[$value->accounts_heads_id]['head_class_name'] = $value->head_class_name;
	            $trailList[$value->accounts_heads_id]['head_sub_class_name'] = $value->head_sub_class_name;*/
	            // $trailList[$value->accounts_heads_id][$value->sub_head][] = $value;

	            $trailList['value'][$value->accounts_heads_id][$value->accounts_sub_heads_id][$value->mxp_acc_classes_id][$value->mxp_acc_head_sub_classes_id][$value->chart_of_acc_id] = $value;

                $trailList['index'][] = $value->accounts_heads_id;
                $trailList['index'][] = $value->accounts_sub_heads_id;
                $trailList['index'][] = $value->mxp_acc_classes_id;
                $trailList['index'][] = $value->mxp_acc_head_sub_classes_id;
                $trailList['index'][] = $value->chart_of_acc_id;
	        
	        }

	        // self::print_me($trailList);
	    	return $trailList;
		}
		else 
			return -1;
    }

	private function checkValidation(Request $request) 
	{
		$validator = Validator::make($request->all(), [
            'TrailbalanceDateSearchFldFrom' => 	'required',
            'TrailbalanceDateSearchFldTo' 	=> 	'required'
        ]);

        return $validator;
	}
}


// '41', '4', '52', NULL, 'loan account(hgujghjg)', 'L/C Purchase', '0.00', '80000.00', '41', '0', '36', '0', '0', '2018-07-03', '1', '1', '123', '68750', '0', '1', '0', '1', '2018-05-16 11:40:51', '2018-05-16 11:40:51'

// ad.head_name_type, ad.accounts_heads_id,
// sd.sub_head, sd.accounts_sub_heads_id,
// cls.head_class_name, cls.mxp_acc_classes_id,
// sub.head_sub_class_name, sub.mxp_acc_head_sub_classes_id,