<?php

namespace App\Http\Controllers\Stock_Management;


use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

use App\Model\MxpStore;

use Validator;
use Exception;
use App;
use Lang;
use DB;

class StoreController extends Controller
{
    public function index()
    {        
        $stores = MxpStore::where('com_group_id',self::getGroupId())
            ->where('company_id',self::getConpanyId())
//            ->where('user_id',self::getUserId())
            ->where('is_deleted',0)
            ->where('status',1)
            ->paginate($this->limitPerPage);

        return view('Stock_Management.store.index',['stores' => $stores]);
    }

    public function addStoreForm()
    {
        return view('Stock_Management.store.add_store');
    }

    public function addStoreAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:mxp_store,name,null,null,company_id,'.self::getConpanyId().',com_group_id,'.self::getGroupId().',user_id,'.self::getUserId().',is_deleted,0,status,1',
            'location' => 'required'
        ]);

        $notSuccess = $this->setStore($validator, $request);

        if ($notSuccess)
            return redirect()->back()->withInput($request->input())->withErrors($notSuccess);

        else
            return redirect()->route('store_list_view');
    }

    private function setStore($validator,Request $request)
    {
        if ($validator->fails())
            return $validator->messages();

        else
        {
            $this->saveStore($request);
            return '';
        }
    }

    private function saveStore(Request $request)
    {
        $store                   =   $this->getStoreObj($request);
        $store->name             =   $request->name;
        $store->location         =   $request->location;
        $store->status           =   $request->isActive;
        $store->com_group_id     =   self::getGroupId();
        $store->company_id       =   self::getConpanyId();
        $store->user_id          =   self::getUserId();
        $store->is_deleted       =   0;
        
        return $store->save();
    }

    private function getStoreObj(Request $request)
    {
        if($request->id)
            $store   =   MxpStore::find($request->id);

        else
            $store   =   new MxpStore();

        return $store;
    }

    public function editStoreForm(Request $request)
    {
        $store   =   $this->getStoreObj($request);

        return view('Stock_Management.store.edit_store',['store'=>$store]);
    }

    public function editStoreAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:mxp_store,name,'.$request->id.',id,company_id,'.self::getConpanyId().',com_group_id,'.self::getGroupId().',user_id,'.self::getUserId().',is_deleted,0,status,1',
            'location' => 'required'
        ]);

        $notSuccess = $this->setStore($validator, $request);

        if ($notSuccess)
            return redirect()->back()->withInput($request->input())->withErrors($notSuccess);

        else
            return redirect()->route('store_list_view');
    }

    public function deleteStoreForm(Request $request)
    {
        $store   =   $this->getStoreObj($request);

        $store->is_deleted = 1;
        $store->save();

        return redirect()->route('store_list_view');
    }
}
