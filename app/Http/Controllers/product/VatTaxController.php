<?php

namespace App\Http\Controllers\product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Mxp_taxvat;
use App\Http\Controllers\Message\StatusMessage;
use Validator;

class VatTaxController extends Controller
{
    public function vatTaxList(Request $request){
    		if($request->session()->get('user_type') == 'super_admin'){
    			
    			$vatTaxes = Mxp_taxvat::get()->where('group_id', '=', $request->session()->get('group_id'));

    		}else if($request->session()->get('user_type') == 'company_user'){
    			$vatTaxes = Mxp_taxvat::get()->where('company_id', '=', $request->session()->get('company_id'));
    		}

    		

 		  return view('product.vat_tax.vat_tax_list', ['vatTaxes' => $vatTaxes]);
    }

	public function addVatTaxForm(Request $request)
    {
			return view('product.vat_tax.add_vat_tax', ['request' => $request]);
	}

	public function addVatTax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:mxp_taxvats,name',
        ]);

        if ($validator->fails())
            return redirect()->back()->withInput($request->input())->withErrors($validator->messages());

        $addVatTax = new Mxp_taxvat();

        $addVatTax->name = $request->name;
        $addVatTax->company_id = $request->company_id;
        $addVatTax->group_id = $request->group_id;
        $addVatTax->user_id = $request->user_id;
        $addVatTax->is_deleted = '0';
        // $addVatTax->calculation_type = $request->calculation_type;
        $addVatTax->status = $request->is_active;
        $addVatTax->save();
		return Redirect()->route('list_vat_tax_view');
	}

	public function delete(Request $request){

		$vatTax = Mxp_taxvat::find($request->id);
        $vatTax->delete();

        StatusMessage::create('vat_tax_message', 'Row Deleted Successfully');

        return redirect()->back();
	}

}
