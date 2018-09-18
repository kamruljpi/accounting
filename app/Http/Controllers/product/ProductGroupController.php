<?php

namespace App\Http\Controllers\product;


use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

use App\MxpProductGroup;

use Validator;
use Exception;
use App;
use Lang;
use DB;

class ProductGroupController extends Controller
{
    public $deleteStatus = 1;
    public $saveStatus   = 0;

    public function index()
    {
        $com_group_id     =   session()->get('group_id');
        $company_id       =   session()->get('company_id')|0;

        $productGroups = MxpProductGroup::get()->where('com_group_id',$com_group_id)->where('company_id',$company_id)->where('is_deleted',0);

        return view('product.product_group.index',['productGroups' => $productGroups]);
    }

    public function addProductGroupForm()
    {
        return view('product.product_group.add_product_group');
    }

    public function addProductGroupAction(Request $request)
    {
        $com_group_id     =   session()->get('group_id');
        $company_id       =   session()->get('company_id')|0;

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:mxp_product_group,name,null,null,company_id,'.$company_id.',com_group_id,'.$com_group_id.',is_deleted,0'
        ]);

        $success = $this->setProductGroup($validator, $request, $this->saveStatus);

        if ($success)
            return redirect()->back()->withInput($request->input())->withErrors($success);

        else
            return redirect()->route('group_product_list_view');
    }

    private function setProductGroup($validator,Request $request, $delete)
    {
        if ($validator->fails())
            return $validator->messages();

        else
        {
            $this->saveProductGroup($request, $delete);
            return '';
        }
    }

    private function saveProductGroup(Request $request, $status)
    {
        $productGroup                  =   $this->getProductGroupObj($request);
        $productGroup->is_deleted      =   $status;

        if($status == $this->saveStatus)
        {
            $productGroup->name            =   $request->name;
            $productGroup->com_group_id    =   session()->get('group_id');
            $productGroup->company_id      =   session()->get('company_id')|0;
            $productGroup->user_id         =   session()->get('user_id');
            $productGroup->is_active       =   $request->isActive;
        }

        return $productGroup->save();
    }

    private function getProductGroupObj(Request $request)
    {
        if($request->id)
            $productGroup   =   MxpProductGroup::find($request->id);

        else
            $productGroup   =   new MxpProductGroup();

        return $productGroup;
    }

    public function editProductGroupForm(Request $request)
    {
        $productGroup   =   $this->getProductGroupObj($request);

        return view('product.product_group.edit_productGroup',['productGroup'=>$productGroup]);
    }

    public function editProductGroupAction(Request $request)
    {
        $com_group_id     =   session()->get('group_id');
        $company_id       =   session()->get('company_id')|0;

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:mxp_product_group,name,'.$request->id.',id,company_id,'.$company_id.',com_group_id,'.$com_group_id.',is_deleted,0'
        ]);

        $success = $this->setProductGroup($validator, $request, $this->saveStatus);

        if ($success)
            return redirect()->back()->withInput($request->input())->withErrors($success);

        else
            return redirect()->route('group_product_list_view');
    }

    public function deleteProductGroupForm(Request $request)
    {
        $this->saveProductGroup($request, $this->deleteStatus);

        return redirect()->route('group_product_list_view');
    }
}
