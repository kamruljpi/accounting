<?php

namespace App\Http\Controllers\journal;

use App\Http\Controllers\API\AccountHeadApi;
use App\Http\Controllers\Message\StatusMessage;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\SubModuleInterface;
use App\MxpCompany;
use App\Model\MxpJournalPosting;
use Auth;
use Illuminate\Http\Request;
use DB;
use Validator;

class JournalController extends Controller implements SubModuleInterface {
	public function listView(Request $request) {
       $data=array();
//		$data['get_all_journal']=DB::select('select * from mxp_journal_posting order by journal_posting_id desc');
		$data['get_all_journal'] = MxpJournalPosting::where('group_id',self::getGroupId())
            ->where('company_id',self::getConpanyId())
            ->where('is_deleted',0)
            ->where('is_active',1)
            ->orderBy('journal_posting_id', 'DESC')
            ->paginate($this->limitPerPage);
		return view('journal.journal_posting_list',$data);
	}

    public function formView(Request $request) {

        $char_of_acc[''] = 'Select';
        $chartOfAccounts = json_decode(AccountHeadApi::chartOfAccount($request));
        foreach ( $chartOfAccounts as $value) {
            $char_of_acc[$value->chart_o_acc_head_id] = $value->acc_final_name;
        }

        $user_role = 'Executive';
        $user_role_id = '5';

        $group_id = session()->get('group_id');
        if (session()->get('user_type') == "company_user") {
            $companies = MxpCompany::get()->where('id', Auth::user()->company_id);
        } else {
            $companies = MxpCompany::get()->where('group_id', $group_id);
        }
        $company=array();
        $company = ['' => 'Select Company'];
        $i = 0;
        foreach ($companies as $com) {
            $company[$com['id']] = $com['name'];
            $i++;
        }

        return view('journal.journal_posting_form',
            [
                //'request' => $request,
                'company' => $company,
                'user_role' => $user_role,
                'user_role_id' => $user_role_id,
                'char_of_acc' => $char_of_acc
            ]);

    }


    public function dataUpload(Request $request) {

//	    return $request->all();
//	    die();

        $messagess = [
            'char_of_acc.*required' 		=> 'Char of acc required.',
            'amount.*required' 				=> 'Amount required.',
            'cf_code.*required' 			=> 'Cf Code required.',
            'description.*required' 		=> 'Description required.',
            'particulars.*required' 		=> 'Chart Of Acc required.',
            'char_of_acc_bottom.required' 	=> 'Char of acc_Bottom required.',
            'amount_bottom.required' 		=> 'Amount_Bottom required.',
            'cf_code_bottom.required' 		=> 'Cf Code_Bottom required.',
            'description_bottom.required' 	=> 'Description_Bottom required.',
            'particulars_bottom.required' 	=> 'Chart Of Acc_Bottom required.',
        ];

        $validator = Validator::make($request->all(), [
            'char_of_acc.*'     	=>'required',
            'amount.*'       		=>'required',
            'cf_code.*'       		=>'required',
            'description.*'     	=>'required',
            'particulars.*'       	=>'required',
            'char_of_acc_bottom'    =>'required',
            'amount_bottom'         =>'required',
            'cf_code_bottom'       	=>'required',
            'description_bottom'    =>'required',
            'particulars_bottom'    =>'required',
        ],$messagess);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $com_group_id = $request['com_group_id'];

        $ran_group_id = self::getRandomGroupId();

        for ($i=0; $i < count($request->char_of_acc); $i++) {

            $input = new MxpJournalPosting;
            $input->transaction_type_id = 0;
            $input->account_head_id = $request->char_of_acc[$i];
            $input->particular = $request->particulars[$i];
            $input->description = $request->description[$i];

            if( $request->transaction_type[$i] == 2)
            {
                $input->transaction_amount_debit = $request->amount[$i];
                $input->transaction_amount_credit = 0;
            }
            else
            {
                $input->transaction_amount_credit = $request->amount[$i];
                $input->transaction_amount_debit = 0;
            }

            $input->reference_id = 1;
            $input->reference_no = 1;
            $input->cf_code = $request->cf_code[$i];
            $input->posting_group_id = $ran_group_id;
            $input->company_id = session()->get('company_id');
            $input->group_id = session()->get('group_id');
            $input->journal_date = $request->journal_date[$i];
            $input->user_id = $this->getUserId() ;
            $input->is_deleted = 0;
            $input->is_active = 1;
            $input->save();
        }
        $input2 = new MxpJournalPosting;
        $input2->transaction_type_id = 0;
        $input2->account_head_id = $request->char_of_acc_bottom;
        $input2->particular = $request->particulars_bottom;
        $input2->description = $request->description_bottom;

        if( $request->transaction_type_bottom == 2)
        {
            $input2->transaction_amount_debit = $request->amount_bottom;
            $input2->transaction_amount_credit = 0;
        }
        else
        {
            $input2->transaction_amount_credit = $request->amount_bottom;
            $input2->transaction_amount_debit = 0;
        }

        $input2->reference_id = 1;
        $input2->reference_no = 1;
        $input2->cf_code = $request->cf_code_bottom;
        $input2->posting_group_id = $ran_group_id;
        $input2->company_id = session()->get('company_id');
        $input2->group_id = session()->get('group_id');
        $input2->journal_date = $request->journal_date_bottom;
        $input2->user_id = $this->getUserId();
        $input2->is_deleted = 0;
        $input2->is_active = 1;
        $input2->save();

        //StatusMessage::create('journal_add', "Journal Added Successfully");
        return response()->json(['success'=>'Journal Added Successfully']);
    }

    public function update(Request $request) {

    }

    public function delete(Request $request) {

    }
}
