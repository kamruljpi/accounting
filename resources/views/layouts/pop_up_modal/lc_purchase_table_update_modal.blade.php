<div id="myModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-2 pull-right">
                    <form action="{{ Route('lc_product_purchase_search_list') }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="dateSearchFldFrom" id="dateSearchFldFrom" value="{{ Session::get('dateSearchFldFrom') }}">
                        <input type="hidden" name="dateSearchFldTo" id="dateSearchFldTo" value="{{ Session::get('dateSearchFldTo') }}">
                        <input type="submit" name="close_lc_purchase_update_modal" id="close_lc_purchase_update_modal" value="Cancel" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                        
                    </form>
                </div>
                {{-- <a href="{{ Route('lc_product_purchase_search_list') }}" class="btn btn-danger pull-right" data-dismiss="modal" aria-label="Close" id="close_lc_purchase_update_modal">Cancel</a> --}}
                <h3 class="modal-title">Update L/C purchase product</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form action="{{ Route('lc_product_purchase_update_action') }}" class="form-horizontal" method="post" id="lc_purchase_update_form_data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="lc_prucahse_product_id" value="{{ $lc_pursache_product[0]->lc_purchase_id }}">
    <!--
                        @if(Session::has('validation_error_in_add_client'))
                            @include('widgets.alert', array('class'=>'danger', 'message'=> Session::get('validation_error_in_add_client') ))
                        @endif -->

                        <div class="alert alert-danger" id="show_error" style="display: none; margin-left: 20px">
                        </div>
                        
                        <div class="purchase_table_input lc_purchase_table">
                            <div class="purchase_table_left col-sm-5">
                                <div class="col-md-12" id="select_client">
                                    <label>Client: </label>
                                    <div id="divForSelectingClient"></div>
                                    {{-- <div class="test" id="test" style="display: none;">I am here</div> --}}
                                    <select class="form-control" name="client" id="client" class="client">
                                        <option value="" disabled="true">Select Client</option>
                                        @foreach($clients as $client)
                                    <option value="{{ $client->chart_o_acc_head_id }}"> {{ $client->acc_final_name }} </option>
                                @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label>Date: </label>
                                    <input  value="{{ $lc_pursache_product[0]->purchase_date }}" class="form-control" type="date" name="date" id="date">
                                </div>

                                <div class="col-md-12">
                                    <label>Invoice No: </label>
                                    <input  value="{{ $lc_pursache_product[0]->invoice_code }}" class="form-control" type="text" name="invoice" id="invoice">
                                </div>

                                <div class="col-md-12">
                                    <label>Product Group: </label>
                                    <select class="form-control" name="product_group" id="product_group">
                                        <option value="" disabled="true">Select Group</option>
                                        @foreach($productGroup as $group)
                                            <option value="{{ $group->id }}" {{ ($lc_pursache_product[0]->product_group_id == $group->id)? "selected":"" }}> {{ $group->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label>Product: </label>
                                    <select class="form-control" name="product" id="product" disabled="true">
                                        <option value="{{ $lc_pursache_product[0]->product_id }}"> {{ $lc_pursache_product[0]->product_details }} </option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label>LC No/ID: </label>
                                    <input  value="{{ $lc_pursache_product[0]->lc_no }}" class="form-control" type="text" name="lc_no" id="lc_no">
                                </div>
                                <div class="col-md-12">
                                    <label>LC Type: </label>
                                    <select class="form-control" name="lc_type">
                                       <!--  <option value="0">Select Type</option> -->
                                        <option value="1">Payment at sight</option>
                                    </select>
                                </div>
                                {{--<div class="col-md-12">--}}
                                    {{--<label>LC Bank Name: </label>--}}
                                    {{--<input  value="{{ $lc_pursache_product[0]->bank_name }}" class="form-control" type="text" name="bank_name" id="bank_name">--}}
                                {{--</div>--}}
                                <div class="col-md-6">
                                    <label>Quantity: </label>
                                    <input value="{{ $lc_pursache_product[0]->quantity }}" class="form-control" type="text" name="quantity" id="quantity">
                                </div>
                                <div class="col-md-6">
                                    <label>L/C Status: </label>
                                    <select class="form-control" name="lc_Status">
                                        <!-- <option value="0">Pending..</option> -->
                                        <option value="1" {{ ($lc_pursache_product[0]->lc_status == 1)? "selected":"" }}>Approved</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>L/C open Bank Name:</label>
                                    <select class="form-control" name="chart_of_acc" id="chart_of_acc">
                                    <option value="">Select Account</option>
                                    @foreach($chart_of_accounts as $chart_of_account)
                                        <option value="{{ $chart_of_account->chart_o_acc_head_id }}" {{ ($lc_pursache_product[0]->account_head_id == $chart_of_account->chart_o_acc_head_id)? "selected":"" }}> {{ $chart_of_account->acc_final_name }} </option>
                                    @endforeach
                                </select>
                                </div>
                                <div class="col-md-6">
                                    <label>L/C Loan account: </label>
                                    <select class="form-control input_required" name="lc_loan_bank" id="lc_loan_bank" required>
                                        <option value="">Select Account</option>
                                        @foreach($lc_Bank_loan as $bank_acc_loan)
                                            <option value="{{ $bank_acc_loan->chart_o_acc_head_id }}" {{ ($lc_pursache_product[0]->lc_loan_account_head_id == $bank_acc_loan->chart_o_acc_head_id)? "selected":"" }}> {{ $bank_acc_loan->acc_final_name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="purchase_table_right col-sm-7">


                                <div class="col-sm-12 input_table_specific">
                                    <div class="table_box_title">
                                        <label>LC Bank Charges In Taka</label>
                                    </div>

                                    <div class="col-md-12">
                                        <label>Swift Charges: </label>
                                        <input  value="{{ $lc_pursache_product[0]->bank_swift_charge }}" class="form-control" type="text" name="swift_charges" id="swift_charges">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Vat(%): </label>
                                        <input  value="{{ isset($lc_pursache_product[0]->vat)?$lc_pursache_product[0]->vat:0 }}" class="form-control" type="text" name="vat" id="vat">
                                    </div>
                                    <div class="col-md-5">
                                        <label>LC Bank Commission: </label>
                                        <input  value="{{ $lc_pursache_product[0]->bank_commission }}" class="form-control" type="text" name="bank_commission" id="bank_commission">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Total Vat: </label>
                                        <input value="{{ ($lc_pursache_product[0]->bank_commission * $lc_pursache_product[0]->vat) / 100 }}" class="form-control" type="text" name="total_vat" id="total_vat" readonly>
                                    </div>

                                    <div class="col-md-12">
                                        <label>LC Application From Charges: </label>
                                        <input  value="{{ $lc_pursache_product[0]->apk_form_charge }}" class="form-control" type="text" name="application_form_charges" id="application_form_charges">
                                    </div>
                                </div>

                                <div class="col-sm-12" style="background-color: #AEB6BF;">
                                    <div class="col-md-6">
                                        <label>LC Amount(USD): </label>
                                        <input  value="{{ $lc_pursache_product[0]->lc_amount_usd }}" class="form-control" type="text" name="lc_amount" id="lc_amount">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Doller Rate: </label>
                                        <input  value="{{ $lc_pursache_product[0]->dollar_rate }}" class="form-control" type="text" name="doller_rate" id="doller_rate">
                                    </div>
                                    <div class="col-md-3">
                                        <label>In Taka: </label>
                                        <input  value="{{ $lc_pursache_product[0]->lc_amount_taka }}" class="form-control" type="text" name="lc_amount_in_taka" id="lc_amount_in_taka" readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Margin(%): </label>
                                        <input  value="{{ $lc_pursache_product[0]->lc_margin_percent }}" class="form-control " type="text" name="margin" id="margin">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Total Margin(USD): </label>
                                        <input  value="{{ $lc_pursache_product[0]->lc_margin / $lc_pursache_product[0]->dollar_rate }}" class="form-control" type="text" name="total_margin" id="total_margin" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Total Margin In Taka: </label>
                                        <input value="{{ $lc_pursache_product[0]->lc_margin }}" class="form-control" type="text" name="total_margin_in_taka" id="total_margin_in_taka" readonly>
                                    </div>

                                    <div class="col-md-6" style="padding-right: 8px;">
                                        <label>Due Payment(USD): </label>
                                        <input  value="{{ $lc_pursache_product[0]->due_payment / $lc_pursache_product[0]->dollar_rate }}" class="form-control" type="text" name="due_payment" id="due_payment" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Due Payment In Taka: </label>
                                        <input value="{{ $lc_pursache_product[0]->due_payment }}" class="form-control" type="text" name="due_payment_in_taka" id="due_payment_in_taka" readonly>
                                    </div>
                                    <div class="col-md-12" style="padding-right: 8px;">
                                        <label>Paid due in taka: </label>
                                        <input class="form-control" type="text" name="paid_due_in_taka" required id="paid_due_in_taka">
                                    </div>
                                </div>
                                    <div class="col-md-6">
                                        <label>Country of Origin: </label>
                                        <input  value="{{ $lc_pursache_product[0]->country_orign }}" class="form-control" type="text" name="country_of_origin" id="country_of_origin">
                                    </div>
                                <div class="col-md-6">
                                    <label>Other Cost In Taka: </label>
                                    <input  value="{{ $lc_pursache_product[0]->others_cost }}" class="form-control" type="text" name="other_cost" id="other_cost">
                                </div>
                                <div class="col-md-4 col-md-offset-8">
                                    <input id="update_lc_product_btn" type="submit" name="update_lc_product_btn" value="Update" class="btn btn-primary" style="margin-top: 10px; font-size: 20px">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer"> 
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset("js/all_product_table.js") }}"></script>
<script type="text/javascript" src="{{ asset("js/custom.js") }}"></script>
<script type="text/javascript">
    $('#myModal').modal({backdrop: 'static', keyboard: false})
</script>
