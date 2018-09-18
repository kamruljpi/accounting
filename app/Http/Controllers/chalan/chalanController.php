<?php

namespace App\Http\Controllers\chalan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class chalanController extends Controller
{
    public function chalanView(){
    	$g_challan=array();
        $clients = $this->getAccHeadClientForSale();
        $get_oreder_no=DB::select('call get_order_noBY_type("sale",' . self::getGroupId() . ',' . self::getConpanyId() . ')');
    	return view('chalan.index')->with(['clients'=>$clients,'orderNo'=>$get_oreder_no,'g_challan'=>$g_challan]);
    }

    public function generteChalan(Request $request){
    	
        $get_chalan=DB::select('call generate_chalan_by_order("'.$request->order_id.'", "sale","'.$request->client_id.'" )');
    	//echo '<pre>';print_r($get_chalan);exit;
        
        if(isset($get_chalan)){
    		return view('chalan.generated-chalan')->with('g_challan',$get_chalan);
    	}
    	
        
    }

}
