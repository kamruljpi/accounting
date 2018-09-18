@extends('layouts.dashboard')
@section('section')
<style type="text/css">
    .table{
            margin-bottom: 5px;

    }
    .purchase_table_input input, .purchase_table_input select{
        width: 90% !important;]
    }
    .input_required{
        /*width: 90% !important;*/
    }
    .req_star{
        position: absolute;
        right: 10px;
        top: 25px;
    }
</style>
<div class="col-sm-12" id="demo">
    <div class="row">
        <div class="col-sm-12">
            @if(count($errors) > 0)
            <div class="alert alert-danger" role="alert">
                @foreach($errors->all() as $error)
                <li>
                    <span>
                        {{ $error }}
                    </span>
                </li>
                @endforeach
            </div>
            @endif

            @if(Session::has('product_sale'))
                @include('widgets.alert', array('class'=>'success', 'message'=> Session::get('product_sale') ))
            @endif
            @if(Session::has('product_sale_warrning'))
                @include('widgets.alert', array('class'=>'danger', 'message'=> Session::get('product_psale_warrning') ))
            @endif
 <form action="{{ Route('product_sale_form') }}" method="post" role="form" id="frm_value" >
          <input  name="_token" type="hidden" value="{{ csrf_token() }}">

            <div class="purchase_table_input sales_table_input">
                <div class="purchase_table_left col-sm-5">
                        {{-- Order and transport no --}}
                        <div class="col-md-12">
                                <div class="col-md-6">
                                    <label>Order No: </label>
                                    <input required class="form-control" type="text" id="order_no" name="order_no" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label>Transport No: </label>
                                    <input required class="form-control" type="text" id="transport_no" name="transport_no" value="" required>
                                </div>
                               
                        </div>

                        <div class="col-md-12">
                                 <div class="col-md-6">
                                    <label>Product Group: </label>
                                    <select class="form-control input_required" name="product_group" id="product_group_sale_lc">
                                        <option value="">Select Group</option>
                                        @foreach($productGroup as $group)
                                            <option value="{{ $group->id }}"> {{ $group->name }} </option>
                                        @endforeach
                                    </select>
                                 </div>
                                <div class="col-md-6">
                                    <label>Sales Date: </label>
                                    <input required class="form-control input_required" type="date" name="sales_date" id="sales_date">
                                </div>
                        </div>

                        {{-- <!-- <div class="col-md-12">
                            <label>Representative: </label>
                            <select class="form-control input_required" name*="representative" id="representative">
                                <option value="">Select Representative</option>
                                @foreach($clients as $representative)
                                    <option value="{{ $representative->user_id }}"> {{ $representative->first_name }} </option>
                                @endforeach
                            </select>
                        </div> --> --}}

                        <div class="col-md-12">
                            <label>Product: </label>
                            <select class="form-control input_required p_name" name="product" id="product" disabled>
                            </select>
                        </div>
                </div>

                <div class="purchase_table_left col-sm-7">

                        <div class="col-md-12">
                                <div class="col-md-6">
                                    <label>Transport Name: </label>
                                    <input required class="form-control input_required" type="text" name="transport_name" id="transport_name">
                                </div>

                                <div class="col-md-6">
                                    <label>Transport Date: </label>
                                    <input required class="form-control input_required" type="date" name="transport_date" id="transport_date">
                                </div>
                        </div>


                        <div class="col-md-12" id="select_client">
                            <label>Party: </label>
                            <div id="divForSelectingClient"></div>
                            <select class="form-control input_required" name="client" id="client" class="client">
                                <option value="">Select Client</option>
                                {{-- <option value="-1">Create new Client</option> --}}
                                @foreach($clients as $client)
                                    <option value="{{ $client->chart_o_acc_head_id }}"> {{ $client->acc_final_name }} </option>
                                @endforeach
                            </select>
                                {{-- <div class="UrgentClientView"></div> --}}
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-6">
                                <label>Invoice No: </label>
                                <input required class="form-control input_required" type="text" name="invoice" id="invoice" readonly>
                            </div>
                            <div class="col-md-6">
                                <button class="btn-primary btn" id="generate_invoice_btn" style="margin-top: 25px; width: 90%;"> Generate Invoice</button>
                            </div>


                        </div>
                </div>

                <div class="col-md-10 vat_tax_row" style="margin-left:15px;">
                        <div class="col-md-12">
                            <label>Vat</label>
                        </div>
                <div class="col-md-{{ round(12/(count($vattaxes))) }}">
                    <input class="form-control" name="vattax_percentage" placeholder="vat (%)" type="text">
                    <input name="vattax_id" type="hidden" value="">
                </div>
                        <?php //$i = 1;?>
                        <!-- @foreach($vattaxes as $vattax) -->
                        <!-- <td>
                            <div class="col-md-{{ round(12/(count($vattaxes))) }}">
                                <input class="form-control" name="vattax_percentage[]" placeholder="{{ $vattax->name }} (%)" type="text">
                                <input name="vattax_id" type="hidden" value="{{ $vattax->id }}">
                            </div>
                        </td> -->
                        <?php //$i++;?>
                       <!--  @endforeach -->
                </div>

                <div class="col-md-12   purchase_table_right_bottom">
                        <div class="col-md-2">
                            <label>Quantity</label>
                            <input required onkeyup="checkAvailability()" class="form-control input_required" readonly type="text" name="quantity">
                        </div>
                        <div class="col-md-2">
                            <label>Unit</label>
                            <input class="form-control input_required" type="text" readonly value="kg" name="unit">
                        </div>  
                        <div class="col-md-3">
                            <label>Sales Price per unit</label>
                            <input required class="form-control input_required" type="text" readonly name="sales_price">
                        </div>
                         <div class="col-md-2">
                            <label>paid amount</label>
                            <input class="form-control input_required" type="text" value="0" name="paid">
                        </div>  
                        <div class="col-md-2">
                            <label>amount to paid</label>
                            <input class="form-control input_required" type="text" readonly name="total_p_amount">
                        </div>
                        <div class="col-md-2">
                            <label>Left</label>
                            <input required class="form-control" type="text" name="available" id="available_q" readonly>
                        </div>
                        <div class="col-md-2">
                            <label>Bonus</label>
                            <input disabled class="form-control" type="text" name="bonus">
                        </div>
                        <div class="col-md-3">
                            <label>Chart of Acc: </label>
                            <select class="form-control input_required chart_acc" name="chart_of_acc" id="chart_of_acc">
                                <option value="">-- Select --</option>
                                @foreach($chartOfAccs as $chartOfAcc)
                                    <option value="{{ $chartOfAcc->chart_o_acc_head_id }}"> {{ $chartOfAcc->acc_final_name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-md-offset-10">
                            <button class="btn btn-success sale_data_add" disabled>Add</button>
                        </div>
                </div>
            </div>
        </form>    
            <div class="purchase_table col-md-12">
            <div class="frm_value"></div>
            </div>
        </div>
    </div>
</div>
@stop

