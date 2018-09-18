<?php

namespace App\Http\Controllers\Accounts_Head;

use App;
use App\Http\Controllers\Controller;
use App\Model\MxpAccountsSubHead;
use App\Model\MxpAccountsHead;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Validator;

class AccountsSubHeadName extends Controller
{
	public $deleteStatus = 1;
	public $saveStatus   = 0;

	public function sub_account_head_index($page = 1)
    {
        $start              =   ($page-1)*$this->limitPerPage;

        $totalData          =   MxpAccountsSubHead::where('company_id',self::getConpanyId())->where('group_id',self::getGroupId())->count();

        
        $sub_accounts_heads = DB::select('call get_all_accounts_sub_head('.self::getGroupId().','.self::getConpanyId().','.$start.','.$this->limitPerPage.')');

        return view('Accounts_head.sub_head_management.sub_account_head_index',[
            'sub_accounts_heads' => $sub_accounts_heads,
            'currentPage'   =>  $page, 
            'totalData'     =>  $totalData, 
            'limitPerPage'  =>  $this->limitPerPage]);
    }

    public function addSubAccountHeadForm()
    {
        
        $accounts_heads = MxpAccountsHead::get()->where('group_id',self::getGroupId())->where('company_id',self::getConpanyId())->where('is_deleted',0)->where('is_active',1);

        return view('Accounts_head.sub_head_management.add_sub_account_name',['accounts_heads' => $accounts_heads]);
    }

    public function addSubAccountHeadAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_name' => 'required',
            'sub_account_name' => 'required'
        ]);

        $notSuccess = $this->setAccountHead($validator, $request, $this->saveStatus);

        if ($notSuccess)
            return redirect()->back()->withInput($request->input())->withErrors($notSuccess);

        else
            return redirect()->route('accounts_sub_head_name_view');
    }

    public function editSubAccountHeadForm(Request $request)
    {

        $accounts_heads   =   MxpAccountsHead::get()->where('group_id',self::getGroupId())->where('company_id',self::getConpanyId())->where('is_deleted',0)->where('is_active',1);

        $sub_accounts_heads = $this->getAccountHeadObj($request);


        return view('Accounts_head.sub_head_management.edit_sub_account_name',['accounts_heads'=>$accounts_heads, 'sub_accounts_heads'=>$sub_accounts_heads]);
    }

    public function editSubAccountHeadAction(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'account_name' => 'required',
            'sub_account_name' => 'required'
        ]);

        $notSuccess = $this->setAccountHead($validator, $request, $this->saveStatus);

        if ($notSuccess)
            return redirect()->back()->withInput($request->input())->withErrors($notSuccess);
        

        else
            return redirect()->route('accounts_sub_head_name_view');
    }

    public function deleteSubAccountHead(Request $request)
    {
        $this->saveAccountHead($request, $this->deleteStatus);

        return redirect()->route('accounts_sub_head_name_view');
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
        $sub_account_head 				= $this->getAccountHeadObj($request);
        $sub_account_head->is_deleted 	= $status;

    	if($status == $this->saveStatus)
    	{
	        $sub_account_head->accounts_heads_id =  $request->account_name;
	        $sub_account_head->sub_head     	 =  $request->sub_account_name;
	        $sub_account_head->is_active         =  $request->isActive;
	        $sub_account_head->group_id          =  self::getGroupId();
	        $sub_account_head->company_id        =  self::getConpanyId();
	        $sub_account_head->user_id           =  self::getUserId();
    	}

        return $sub_account_head->save();
    }

    private function getAccountHeadObj(Request $request)
    {
        if($request->id)
            $sub_account_head   =   MxpAccountsSubHead::find($request->id);

        else
            $sub_account_head   =   new MxpAccountsSubHead();

        return $sub_account_head;
    }
}
