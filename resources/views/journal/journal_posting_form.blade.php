@extends('layouts.dashboard')
{{-- @extends('layouts.pop_up_modal.client_create_modal') --}}
{{-- @extends('layouts.pop_up_modal.sale_products_modal')  --}}
{{-- @section('page_heading','Add Role') --}}
@section('section')
    <style type="text/css">
        .table{
            margin-bottom: 5px;

        }
        .purchase_table_input input, .purchase_table_input select{
            /*width: 100% !important;*/
        }
        .input_required{
            /*width: 90% !important;*/
        }
        .req_star{
            position: absolute;
            right: 15px;
            top: 25px;
        }
        label{
            width: 100%;
        }
        .col-md-12, .col-md-4, .col-md-3, .col-md-5 {
            padding-left: 0px;
            padding-right: 0px;
        }
        .col-md-2{
            padding-left: 0px;
            padding-right: 0px;
        }
        #journal_post_top{
            background-color:#C8C8C8;
        }
        #journal_post_bottom{
            background-color:#C8C8C8;
        }

    </style>
    <div class="col-sm-12" id="demo">
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger" role="alert" style="display:none">
                    <ul></ul>
                </div>
                <div class="alert alert-success" role="alert" style="display:none">
                </div>

                @if(Session::has('journal_add'))
                    @include('widgets.alert', array('class'=>'success', 'message'=> Session::get('journal_add') ))
                @endif
                {{--
                @if(Session::has('product_purchase_warrning'))
                    @include('widgets.alert', array('class'=>'danger', 'message'=> Session::get('product_purchase_warrning') ))
                @endif --}}

                {{ Form::open(array('url' => Route('journal_posting_form_action'), 'method' => 'post', 'id'=> 'journal_post_form_id')) }}
                <div class="all_input_field">
                    <li class="all_input_li_1">
                        <div id="journal_post_top" class="col-md-12">
                            <div class="col-md-4">
                                <label class="company_id_span">Company</label>
                                {{ Form::select('company_id[]',$company, null,['class'=>'company_id form-control', 'name'=>'company_id[]']) }}

                                <label class="user_role_span">User (Role)</label>
                                {{ Form::select('user_role[]', [], null, ['class'=>'user_role form-control', 'disabled'=> true,'name'=>'user_role[]']) }}


                                <label class="amount_span">Amount</label>
                                {{ Form::text('amount[]', '', ['class'=>'amount form-control', 'id'=>'amount_top','name'=>'amount[]']) }}

                                <label class="description_span">Description</label>
                                {{ Form::text('description[]', '', ['class'=>'description form-control','name'=>'description[]']) }}

                            </div>
                            <div class="col-md-4">
                                <label class="journal_date_span">Date</label>
                                {{ Form::date('journal_date[]',null,['class'=>'journal_date form-control','name'=>'journal_date[]']) }}

                                <label class="members_span"> Members</label>
                                {{ Form::select('members[]', [], null, ['class'=>'members form-control', 'disabled'=> true,'name'=>'members[]']) }}


                                <label class="transaction_span">Transaction Type</label>
                                {{ Form::select('transaction_type[]',[ 'Select Type','1' => 'Payment','2' => 'Receive'],null, ['class'=>'transaction_type form-control','name'=>'transaction_type[]']) }}
                            </div>
                            <div class="col-md-4">
                                <label class="voucher_num_span">Voucher Number</label>
                                {{ Form::text('voucher_num[]', '', ['class'=>'voucher_num form-control', 'readonly'=>'true','name'=>'voucher_num[]']) }}

                                <label class="char_of_acc_span">Chart Of Acc</label>
                                {{ Form::select('char_of_acc[]', $char_of_acc, null, ['class'=>'char_of_acc form-control','name'=>'char_of_acc[]','id'=>'chart_of_acc_top']) }}

                                <label class="cf_code_span">CF Code</label>
                                {{ Form::text('cf_code[]', '', ['class'=>'cf_code form-control','name'=>'cf_code[]']) }}


                                {{ Form::hidden('particulars[]', '', ['class'=>'particulars form-control','name'=>'particulars[]', 'id'=>'particulars']) }}

                            </div>
                        </div>
                    </li>
                    <li class="input_li_btm">
                        <div id="journal_post_bottom" class="col-md-12 journal_post_bottom">
                            <div class="col-md-4">
                                <label class="company_id_bottom_span">Company</label>
                                {{ Form::select('company_id',$company, null,['class'=>'company_id_bottom form-control','name'=>'company_id_bottom']) }}

                                <label class="user_role_bottom_span">User (Role)</label>
                                {{ Form::select('user_role', [], null, ['class'=>'user_role_bottom form-control', 'disabled'=> true,'name'=>'user_role_bottom']) }}

                                <label class="amount_bottom_span">Amount</label>
                                {{ Form::text('amount_bottom', '', ['class'=>'amount_bottom form-control', 'id'=>'amount_bottom','name'=>'amount_bottom']) }}

                                <label class="description_bottom_span">Description</label>
                                {{ Form::text('description', '', ['class'=>'description_bottom form-control','name'=>'description_bottom']) }}


                            </div>
                            <div class="col-md-4">
                                <label class="journal_date_bottom_span">Date</label>
                                {{ Form::date('journal_date_bottom',null,['class'=>'journal_date_bottom form-control','name'=>'journal_date_bottom']) }}


                                <label class="members_span">Members</label>
                                {{ Form::select('members_bottom', [], null, ['class'=>'members_bottom form-control','disabled'=> true,'name'=>'members_bottom']) }}

                                <label class="transaction_span">Transaction Type</label>
                                {{ Form::select('transaction_type_bottom',[ 'Select Type','1' => 'Payment','2' => 'Receive'], null, ['class'=>'transaction_type_bottom form-control','name'=>'transaction_type_bottom']) }}
                            </div>
                            <div class="col-md-4">
                                <label class="voucher_num_bottom_span">Voucher Number</label>
                                {{ Form::text('voucher_num', '', ['class'=>'voucher_num_bottom form-control', 'readonly'=>'true','name'=>'voucher_num_bottom']) }}

                                <label class="char_of_acc_bottom_span">Chart Of Acc</label>
                                {{ Form::select('char_of_acc', $char_of_acc, null, ['class'=>'char_of_acc_bottom form-control','name'=>'char_of_acc_bottom','id'=>'chart_of_acc_btm']) }}

                                <label class="cf_code_bottom_span">CF Code</label>
                                {{ Form::text('cf_code', '', ['class'=>'cf_code_bottom form-control','name'=>'cf_code_bottom']) }}


                                {{ Form::hidden('particulars', '', ['class'=>'particulars_bottom form-control','readonly'=>'true','name'=>'particulars_bottom','id'=>'particulars_bottom']) }}

                            </div>
                        </div>
                </div>
                </li>
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="company_id[]" type="hidden" value="{{ session()->get('company_id') }}">
                <input name="com_group_id[]" type="hidden" value="{{ session()->get('group_id') }}">

                <div class="col-md-12">
                    <div class="col-md-2">
                        <button class="form-control" id="journal_post_add_row">Add Row</button>
                    </div>
                    <div class="col-md-2">
                        <button class="form-control" id="journal_delete_row">Delete Row</button>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4">
                        {{ Form::submit('Submit', ['class'=>'form-control', 'id' => 'journal_post_form_submit_id']) }}
                    </div>
                </div>

                {{ Form::close() }}

            </div>
        </div>
    </div>
@stop
