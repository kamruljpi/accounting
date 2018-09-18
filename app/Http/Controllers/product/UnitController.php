<?php

namespace App\Http\Controllers\product;


use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

use App\MxpUnit;

use Validator;
use Exception;
use App;
use Lang;
use DB;

class UnitController extends Controller
{
    public $deleteStatus = 1;
    public $saveStatus   = 0;

    public function index()
    {
        $com_group_id     =   session()->get('group_id');
        $company_id       =   session()->get('company_id')|0;
        
        $units = MxpUnit::get()->where('com_group_id',$com_group_id)->where('company_id',$company_id)->where('is_deleted',0);

        return view('product.unit.index',['units' => $units]);
    }

    public function addUnitForm()
    {
        return view('product.unit.add_unit');
    }

    public function addUnitAction(Request $request)
    {
        $com_group_id     =   session()->get('group_id');
        $company_id       =   session()->get('company_id')|0;

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:mxp_unit,name,null,null,company_id,'.$company_id.',com_group_id,'.$com_group_id.',is_deleted,0'
        ]);

        $success = $this->setUnit($validator, $request, $this->saveStatus);

        if ($success)
            return redirect()->back()->withInput($request->input())->withErrors($success);

        else
            return redirect()->route('unit_list_view');
    }

    private function setUnit($validator,Request $request, $status)
    {
        if ($validator->fails())
            return $validator->messages();

        else
        {
            $this->saveUnit($request, $status);
            return '';
        }
    }

    private function saveUnit(Request $request, $status)
    {
        $unit                   =   $this->getUnitObj($request);
        $unit->is_deleted       =   $status;

        if($status == $this->saveStatus)
        {
            $unit->name             =   $request->name;
            $unit->com_group_id     =   session()->get('group_id');
            $unit->company_id       =   session()->get('company_id')|0;
            $unit->user_id          =   session()->get('user_id');
            $unit->is_active        =   $request->isActive;
        }

        return $unit->save();
    }

    private function getUnitObj(Request $request)
    {
        if($request->id)
            $unit   =   MxpUnit::find($request->id);

        else
            $unit   =   new MxpUnit();

        return $unit;
    }

    public function editUnitForm(Request $request)
    {
        $unit   =   $this->getUnitObj($request);

        return view('product.unit.edit_unit',['unit'=>$unit]);
    }

    public function editUnitAction(Request $request)
    {
        $com_group_id     =   session()->get('group_id');
        $company_id       =   session()->get('company_id')|0;

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:mxp_unit,name,'.$request->id.',id,company_id,'.$company_id.',com_group_id,'.$com_group_id.',is_deleted,0'
        ]);

        $success = $this->setUnit($validator, $request, $this->saveStatus);

        if ($success)
            return redirect()->back()->withInput($request->input())->withErrors($success);

        else
            return redirect()->route('unit_list_view');
    }

    public function deleteUnitForm(Request $request)
    {
        $this->saveUnit($request, $this->deleteStatus);

        return redirect()->route('unit_list_view');
    }
}
