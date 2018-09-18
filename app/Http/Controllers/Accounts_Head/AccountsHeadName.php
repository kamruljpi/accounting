<?php

namespace App\Http\Controllers\Accounts_Head;

use App;
use App\Http\Controllers\Controller;
use App\Model\MxpAccountsHead;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Validator;

class AccountsHeadName extends Controller
{
	public $deleteStatus = 1;
	public $saveStatus   = 0;

	public function account_head_index()
    {
        $accounts_heads = MxpAccountsHead::where('group_id',self::getGroupId())->where('company_id',self::getConpanyId())->where('is_deleted',0)->paginate($this->limitPerPage);

        return view('Accounts_head.head_name_management.account_head_index',['accounts_heads'=>  $accounts_heads]);
    }

    public function addAccountHeadForm()
    {
        return view('Accounts_head.head_name_management.add_account_name');
    }

    public function addAccountHeadAction(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'account_name' => 'required',
            'account_code' => 'required|unique:mxp_accounts_heads,account_code,null,null,company_id,'.self::getConpanyId().',group_id,'.self::getGroupId().',user_id,'.self::getUserId().',is_deleted,0,is_active,1'
        ]);

        $arr = (array)$validator->messages();

        // dd((array)$validator->messages());
        // dd(extract($validator->messages()));

        $notSuccess = $this->setAccountHead($validator, $request, $this->saveStatus);

        if ($notSuccess){

            // self::print_me($request->input());
            return redirect()->back()->withInput($request->input())->withErrors($notSuccess);
        }

        else
            return redirect()->route('accounts_head_name_view');
    }

    public function editAccountHeadForm(Request $request)
    {
        $account_head   =   $this->getAccountHeadObj($request);

        // self::print_me($account_head);

        return view('Accounts_head.head_name_management.edit_account_name',['account_head'=>$account_head]);
    }

    public function editAccountHeadAction(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'account_name' => 'required',
            'account_code' => 'required|unique:mxp_accounts_heads,account_code,'.$request->id.',accounts_heads_id,company_id,'.self::getConpanyId().',group_id,'.self::getGroupId().',user_id,'.self::getUserId().',is_deleted,0,is_active,1'
        ]);

        $notSuccess = $this->setAccountHead($validator, $request, $this->saveStatus);

        if ($notSuccess)
            return redirect()->back()->withInput($request->input())->withErrors($notSuccess);

        else
            return redirect()->route('accounts_head_name_view');
    }

    public function deleteAccountHead(Request $request)
    {
        $this->saveAccountHead($request, $this->deleteStatus);

        return redirect()->route('accounts_head_name_view');
    }

    private function setAccountHead($validator,Request $request, $status)
    {
        if ($validator->fails())
            return $validator->messages();

        else
        {
            $this->saveAccountHead($request, $status);
            return '';
        }
    }

    private function saveAccountHead(Request $request, $status)
    {
        $account_head 				= $this->getAccountHeadObj($request);
        $account_head->is_deleted 	= $status;

    	if($status == $this->saveStatus)
    	{
	        $account_head->head_name_type   =   $request->account_name;
	        $account_head->account_code     =   $request->account_code;
	        $account_head->is_active        =   $request->isActive;
	        $account_head->group_id         =   self::getGroupId();
	        $account_head->company_id       =   self::getConpanyId();
	        $account_head->user_id          =   self::getUserId();
    	}

        return $account_head->save();
    }

    private function getAccountHeadObj(Request $request)
    {
        if($request->id)
            $account_head   =   MxpAccountsHead::find($request->id);

        else
            $account_head   =   new MxpAccountsHead();

        return $account_head;
    }
}
