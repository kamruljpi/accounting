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

use DB;
use Validator;

class ChartOfAccManagement extends Controller
{
	public $deleteStatus = 1;
	public $saveStatus   = 0;

	public function index($page = 1)
    {
        $start              =   ($page-1)*$this->limitPerPage;

        $totalData          =   MxpChartOfAccHead::where('company_id',self::getConpanyId())->where('group_id',self::getGroupId())->count();

        $chart_of_accs      =   DB::select('call get_all_chart_of_accounts('.self::getGroupId().','.self::getConpanyId().','.$start.','.$this->limitPerPage.')');

        return view('Accounts_head.chart_of_acc_management.index',[
            'chart_of_accs' =>  $chart_of_accs,
            'currentPage'   =>  $page, 
            'totalData'     =>  $totalData, 
            'limitPerPage'  =>  $this->limitPerPage]);
    }

    public function createView(Request $request)
    {
        $com_group_id       =   session()->get('group_id');
        $company_id         =   session()->get('company_id')|0;
        $user_id            =   session()->get('user_id');

        $acc_sub_class      =   null;
        $acc_class          =   null;
        $accounts_sub_heads = null;
        $accountHead        =   new AccountHeadApi();

        if($request->old())
        {
            $parentIds      =   $this->getParentId($request->old(), null);
            $acc_sub_class  =   $accountHead->getAccSubClass(null, $parentIds['mxp_acc_classes_id']);
            $acc_class      =   $accountHead->getAccClass(null, $parentIds['accounts_sub_heads_id']);
            $accounts_sub_heads = $accountHead->getAccountsSubHead(null, $parentIds['accounts_heads_id']);
        }

        $accounts_heads   = MxpAccountsHead::get()->where('group_id',$com_group_id)->where('company_id',$company_id)->where('is_deleted',0)->where('is_active',1);


        return view('Accounts_head.chart_of_acc_management.create',[
            'accounts_heads'     => $accounts_heads,
            'acc_sub_class'      => json_decode($acc_sub_class),
            'acc_class'          => json_decode($acc_class),
            'accounts_sub_heads' => json_decode($accounts_sub_heads),
            ]);
    }

    public function createAction(Request $request)
    {
        $validator = $this->checkValidation($request);

        $notSuccess = $this->setData($validator, $request, $this->saveStatus);

        if ($notSuccess)
            return redirect()->back()->withInput($request->input())->withErrors($notSuccess);

        else
            return redirect()->route('chart_of_acc_index');
    }

    public function updateView(Request $request)
    {
        $com_group_id     =   session()->get('group_id');
        $company_id       =   session()->get('company_id')|0;
        $user_id          =   session()->get('user_id');

        $chart_of_acc_name=   $this->getDataObj($request);

        $parentIds        =   $this->getParentId($request->old(), $chart_of_acc_name);

        $accountHead      =   new AccountHeadApi();

        $acc_sub_class    =   $accountHead->getAccSubClass(null, $parentIds['mxp_acc_classes_id']);

        $acc_class 		  =   $accountHead->getAccClass(null, $parentIds['accounts_sub_heads_id']);

        $accounts_heads   =   MxpAccountsHead::get()->where('group_id',$com_group_id)->where('company_id',$company_id)->where('is_deleted',0)->where('is_active',1);

        $accounts_sub_heads = $accountHead->getAccountsSubHead(null, $parentIds['accounts_heads_id']);


        return view('Accounts_head.chart_of_acc_management.edit',[
            'acc_sub_class'		=> 	json_decode($acc_sub_class),
            'acc_class'     	=>	json_decode($acc_class),
            'accounts_heads'    =>  $accounts_heads,
            'accounts_sub_heads'=>  json_decode($accounts_sub_heads),
            'chart_of_acc_name' =>  $chart_of_acc_name
            ]);
    }

    public function updateAction(Request $request)
    {
        $validator = $this->checkValidation($request);

        $notSuccess = $this->setData($validator, $request, $this->saveStatus);

        if ($notSuccess)
            return redirect()->back()->withInput($request->input())->withErrors($notSuccess);

        else
            return redirect()->route('chart_of_acc_index');
    }

    public function deleteAction(Request $request)
    {
        $this->saveData($request, $this->deleteStatus);

        return redirect()->route('chart_of_acc_index');
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
            $dataObj->mxp_acc_head_sub_classes_id  =   $request->account_head_sub_class_name;
	        $dataObj->mxp_acc_classes_id   	       =   $request->account_head_class_name;
	        $dataObj->accounts_heads_id    	       =   $request->account_head_name;
            $dataObj->accounts_sub_heads_id	       =   $request->account_sub_head_name;
	        $dataObj->acc_final_name  	           =   $request->chart_of_acc_name;
	        $dataObj->is_active            	       =   $request->isActive;
	        $dataObj->group_id             	       =   self::getGroupId();
	        $dataObj->company_id           	       =   self::getConpanyId();
	        $dataObj->user_id              	       =   self::getUserId();
    	}

        return $dataObj->save();
    }

    private function getDataObj(Request $request)
    {
        if($request->id)
            $dataObj   =   MxpChartOfAccHead::find($request->id);

        else
            $dataObj   =   new MxpChartOfAccHead();

        return $dataObj;
    }

    private function checkValidation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_head_sub_class_name'   => 'required',
            'account_head_class_name'       => 'required',
            'account_head_name'             => 'required',
            'account_sub_head_name'         => 'required',
            'chart_of_acc_name'             => 'required'
        ]);
        return $validator;
    }

    private function getParentId($oldParentId, $chart_of_acc_name)
    {
        if($oldParentId)
        {
            $parentIds['mxp_acc_classes_id'] = $oldParentId['account_head_class_name'];
            $parentIds['accounts_sub_heads_id'] = $oldParentId['account_sub_head_name'];
            $parentIds['accounts_heads_id'] = $oldParentId['account_head_name'];
        }
        else
        {
            $parentIds['mxp_acc_classes_id'] = $chart_of_acc_name->mxp_acc_classes_id;
            $parentIds['accounts_sub_heads_id'] = $chart_of_acc_name->accounts_sub_heads_id;
            $parentIds['accounts_heads_id'] = $chart_of_acc_name->accounts_heads_id;
        }
        return $parentIds;
    }
}



