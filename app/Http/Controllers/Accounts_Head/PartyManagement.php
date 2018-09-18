<?php

namespace App\Http\Controllers\Accounts_Head;

use App;
use App\Http\Controllers\Controller;
use App\Model\MxpAccountsHead;
use App\Model\MxpAccountsSubHead;
use App\Model\MxpAccClass;
use App\Model\MxpAccHeadSubClass;
use App\Model\MxpChartOfAccHead;
use App\Http\Controllers\API\AccountHeadApi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Validator;

use DB;


class PartyManagement extends Controller
{
    public function indexView(){

    	$chart_of_accs  =  DB::table('mxp_acc_classes as mac')->where('mac.mxp_acc_classes_id','=','83')
    	->join('mxp_accounts_heads as mah', 'mac.accounts_heads_id','=','mah.accounts_heads_id')
    	->join('mxp_accounts_sub_heads as mash', 'mac.accounts_sub_heads_id','=','mash.accounts_sub_heads_id')
    	->join('mxp_acc_head_sub_classes as mahsc', 'mac.mxp_acc_classes_id','=','mahsc.mxp_acc_classes_id')
    	->join('mxp_chart_of_acc_heads as mcah', 'mahsc.mxp_acc_head_sub_classes_id','=','mcah.mxp_acc_head_sub_classes_id')
    	->SELECT('mah.head_name_type','mash.sub_head','mac.head_class_name','mahsc.head_sub_class_name','mcah.acc_final_name','mahsc.mxp_acc_head_sub_classes_id','mac.mxp_acc_classes_id','mac.accounts_heads_id','mash.accounts_sub_heads_id','mcah.is_active','mcah.chart_o_acc_head_id')
    	->get();
    	 //print_r($chart_of_accs);exit;

    	return view('Accounts_head.party_management.party_view',[
			'chart_of_accs'       =>  $chart_of_accs]);
    }

    public function partyView(){

    	$chart_of_accs =  DB::table('mxp_acc_head_sub_classes as mahc')
    	->where('mahc.mxp_acc_classes_id','=','83')
    	->where('mahc.accounts_heads_id','=','1')
    	->where('mahc.accounts_sub_heads_id','=','51')
    	->get();
    	// print_r($chart_of_accs);exit;

    	return view('Accounts_head.party_management.add_party',[
			'chart_of_accs'       =>  $chart_of_accs]);

    }

    public function addParty(Request $request){

      $validator = Validator::make($request->all(), [
            'chart_of_acc_name'                  => 'required',
            'mxp_acc_head_sub_classes_id'        => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->input())->withErrors($validator->messages());
        }

    	$ChartOfAccHead  = new MxpChartOfAccHead();

    	$ChartOfAccHead->acc_final_name = $request->chart_of_acc_name;
    	$ChartOfAccHead->mxp_acc_head_sub_classes_id = $request->mxp_acc_head_sub_classes_id;
    	$ChartOfAccHead->mxp_acc_classes_id = $request->acc_classes_id;
    	$ChartOfAccHead->accounts_heads_id = $request->accounts_heads_id;
    	$ChartOfAccHead->accounts_sub_heads_id = $request->accounts_sub_heads_id;
    	$ChartOfAccHead->company_id = self::getConpanyId();
    	$ChartOfAccHead->group_id = self::getGroupId();
    	$ChartOfAccHead->user_id = session()->get('user_id');
    	$ChartOfAccHead->is_deleted = '0';
    	$ChartOfAccHead->is_active = $request->isActive;
    	$ChartOfAccHead->save();
        //StatusMessage::create('party_index', "Party Saved Successfully");
    	return redirect()->route('party_index');
    }
     public function updateParty(Request $request){
        
        $values = DB::table('mxp_chart_of_acc_heads as mach')
        ->select('mach.*','mahsc.head_sub_class_name')
        ->where('mach.chart_o_acc_head_id', $request->id)
        ->join('mxp_acc_head_sub_classes as mahsc', 'mach.mxp_acc_head_sub_classes_id','=','mahsc.mxp_acc_head_sub_classes_id')
        ->get();

        $acc_sub_head_class = DB::table('mxp_chart_of_acc_heads as ach')
        ->where('chart_o_acc_head_id', $request->id)
        ->join('mxp_acc_head_sub_classes as ahsc', 'ach.mxp_acc_classes_id','=','ahsc.mxp_acc_classes_id')
        ->get();
        return view('Accounts_head.party_management.edit',[
            'values'               => $values,
            'acc_sub_head_class'  => $acc_sub_head_class]);

    }


    public function updateAction(Request $request){
        
        $validator = Validator::make($request->all(), [
            'chart_of_acc_name'                  => 'required',
            'mxp_acc_head_sub_classes_id'        => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->input())->withErrors($validator->messages());
        }

        $updateAccHead = MxpChartOfAccHead::find($request->chart_o_acc_head_id);
       
        $updateAccHead->acc_final_name = $request->chart_of_acc_name;
        $updateAccHead->mxp_acc_head_sub_classes_id = $request->mxp_acc_head_sub_classes_id;
        $updateAccHead->is_active = $request->isActive;
        $updateAccHead->save();
        //StatusMessage::create('party_index', "Party Updated Successfully");
        return redirect()->route('party_index');
    }

  
    private function checkValidation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_head_class_name'   => 'required',
            'account_head_name'         => 'required',
            'account_sub_head_name'     => 'required',
            'acc_sub_class_name'        => 'required'
        ]);
        return $validator;
    }

}
