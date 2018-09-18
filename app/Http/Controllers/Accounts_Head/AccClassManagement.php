<?php

namespace App\Http\Controllers\Accounts_Head;

use App;
use App\Http\Controllers\Controller;
use App\Model\MxpAccountsHead;
use App\Model\MxpAccountsSubHead;
use App\Model\MxpAccClass;
use App\Http\Controllers\API\AccountHeadApi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

use Validator;
use DB;

class AccClassManagement extends Controller
{
	public $deleteStatus = 1;
	public $saveStatus   = 0;
    private $accountHead;

    public function AccClassManagement()
    {
        $accountHead = new AccountHeadApi();
    }

	public function index($page = 1)
    {
        $start              =   ($page-1)*$this->limitPerPage;

        $totalData          =   MxpAccClass::where('company_id',self::getConpanyId())->where('group_id',self::getGroupId())->count();

        $acc_class = DB::select('call get_all_acc_class('.self::getGroupId().','.self::getConpanyId().','.$start.','.$this->limitPerPage.')');

        return view('Accounts_head.acc_class_management.index',[
            'acc_class'     =>  $acc_class,
            'currentPage'   =>  $page, 
            'totalData'     =>  $totalData, 
            'limitPerPage'  =>  $this->limitPerPage]);
    }

    public function createView(Request $request)
    {

        $accounts_sub_heads = null;
        $accountHead      =   new AccountHeadApi();

        if($request->old())
        {
            $parentIds        =   $this->getParentId($request->old(), null);
            $accounts_sub_heads = $accountHead->getAccountsSubHead(null, $parentIds['accounts_heads_id']);
        }

        $accounts_heads = MxpAccountsHead::get()->where('group_id',self::getGroupId())->where('company_id',self::getConpanyId())->where('is_deleted',0)->where('is_active',1);


        return view('Accounts_head.acc_class_management.create',['accounts_heads' => $accounts_heads, 'accounts_sub_heads' => json_decode($accounts_sub_heads)]);
    }

    public function createAction(Request $request)
    {
        $validator = $this->ValidationCheck($request);

        $notSuccess = $this->setData($validator, $request, $this->saveStatus);

        if ($notSuccess)
            return redirect()->back()->withInput($request->input())->withErrors($notSuccess);

        else
            return redirect()->route('acc_class_index');
    }

    public function updateView(Request $request)
    {

        $acc_class_head   =   $this->getDataObj($request);

        $parentIds = $this->getParentId($request->old(), $acc_class_head);

        $accountHead      =   new AccountHeadApi();

        $accounts_heads   =   MxpAccountsHead::get()->where('group_id',self::getGroupId())->where('company_id',self::getConpanyId())->where('is_deleted',0)->where('is_active',1);
        
        $accounts_sub_heads   =   $accountHead->getAccountsSubHead(null, $parentIds['accounts_heads_id']);


        return view('Accounts_head.acc_class_management.edit',[
            'acc_class_head'=>$acc_class_head,
            'accounts_heads' => $accounts_heads,
            'accounts_sub_heads' => json_decode($accounts_sub_heads)
            ]);
    }

    public function updateAction(Request $request)
    {
        $validator = $this->ValidationCheck($request);

        $notSuccess = $this->setData($validator, $request, $this->saveStatus);

        if ($notSuccess)
            return redirect()->back()->withInput($request->input())->withErrors($notSuccess);

        else
            return redirect()->route('acc_class_index');
    }

    public function deleteAction(Request $request)
    {
        $this->saveData($request, $this->deleteStatus);

        return redirect()->route('acc_class_index');
    }

    private function setData($validator,Request $request, $status)
    {
        if ($validator->fails())
            return $validator->messages();
        else
        {
            $this->saveData($request, $status);
            return '';
        }
    }

    private function saveData(Request $request, $status)
    {
        $dataObj 				= $this->getDataObj($request);
        $dataObj->is_deleted 	= $status;

    	if($status == $this->saveStatus)
    	{
	        $dataObj->accounts_heads_id    =   $request->account_head_name;
            $dataObj->accounts_sub_heads_id=   $request->account_sub_head_name;
	        $dataObj->head_class_name      =   $request->acc_class_name;
	        $dataObj->is_active            =   $request->isActive;
	        $dataObj->group_id             =   self::getGroupId();
	        $dataObj->company_id           =   self::getConpanyId();
	        $dataObj->user_id              =   self::getUserId();
    	}

        return $dataObj->save();
    }

    private function getDataObj(Request $request)
    {
        if($request->id)
            $dataObj   =   MxpAccClass::find($request->id);

        else
            $dataObj   =   new MxpAccClass();

        return $dataObj;
    }

    private function ValidationCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_head_name'     => 'required',
            'account_sub_head_name' => 'required',
            'acc_class_name'        => 'required'
        ]);
        return $validator;
    }

    private function getParentId($oldParentId, $acc_class_head)
    {
        if($oldParentId)
        {
            $parentIds['accounts_heads_id'] = $oldParentId['account_head_name'];
        }
        else
        {
            $parentIds['accounts_heads_id'] = $acc_class_head->accounts_heads_id;
        }
        return $parentIds;
    }
}


