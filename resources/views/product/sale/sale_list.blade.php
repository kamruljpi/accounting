@extends('layouts.dashboard')
@section('page_heading',trans('others.heading_sale_products_list_label'))
@section('section')

<style type="text/css">
    .table{
            margin-bottom: 5px;
    }
</style>

<div class="col-sm-12" id="demo">
    <div class="row">
        <div class="col-sm-12">
            @if(count($errors) > 0)
                    <div class="alert alert-danger" role="alert">
                        @foreach($errors->all() as $error)
                          <li><span>{{ $error }}</span></li>
                        @endforeach
                    </div>
            @endif

            @if(Session::has('purchase_table'))
                @include('widgets.alert', array('class'=>'success', 'message'=> Session::get('purchase_table') ))
            @endif

                <form role="form" action="{{ Route('product_sale_list_search') }}" method="post">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}" required>

                    <div class="form-group row">

                        <style type="text/css">
                            input{
                                width: 100% !important;
                            }
                        </style>

                        <div class="col-md-3">

                            <select name="client_id" class="myselect input_required" required>
                                <option value="">{{ trans('others.select_company_option_label') }}</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->chart_o_acc_head_id }}"{{ ((Session::get('client_id_for_select_field')) == $client->chart_o_acc_head_id)? "selected":"" }}
                                     > {{ $client->acc_final_name }} </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select name="invoice_id" class="myselect">
                                <option value="">{{ trans('others.option_select_invoice_label') }}</option>
                                @foreach($invoice_codes as $invoice_code)
                                    <option value="{{ $invoice_code->id }}" {{ ((Session::get('invoice_id_for_select_field')) == $invoice_code->id)? "selected":"" }}>{{ $invoice_code->invoice_code }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <input type="date" class="form-control" placeholder="{{ trans('others.search_date_placeholder') }}" name="dateSearchFldFrom" id="dateSearchFldFrom" value="{{ Session::get('fromDate')}}" >
                            <p id="search"></p>
                        </span>
                        </div>

                        <div class="col-md-2">
                            <input type="date" class="form-control" placeholder="{{ trans('others.search_date_placeholder') }}" name="dateSearchFldTo" id="dateSearchFldTo" value="{{ Session::get('toDate') }}">
                            <p id="search"></p>
                        </span>
                        </div>

                        {{ Session::forget('client_id_for_select_field') }}
                        {{ Session::forget('invoice_id_for_select_field') }}
                        {{ Session::forget('fromDate') }}
                        {{ Session::forget('toDate') }}
                        {{ Session::forget('stock_status') }}

                        <div class="col-md-2">
                            <input class="form-control btn btn-primary btn-outline" type="submit" value="{{ trans('others.search_btn') }}" >
                        </span>
                        </div>
                    </div>
                </form>


                <table class="table table-bordered purchase_table" id="tblSearch">
                    <thead>
                        <tr>
                            <th class="">{{ trans('others.serial_no_label') }}</th>
                            <th class="">{{ trans('others.date_label') }}</th>
                            <th class="">{{ trans('others.company_name_label') }}</th>
                            <th class="">{{ trans('others.invoice_no_label') }}</th>
                            <th class="">{{ trans('others.product_name_label') }}</th>
                            <th class="">{{ trans('others.quantity_per_kg_label') }}</th>
                            <th class="">{{ trans('others.unit_price_per_kg_label') }}</th>
                            <th class="">{{ trans('others.price') }}</th>
                            <th class="">{{ trans('others.total_uptodate_quantity_label') }}</th>
                            @foreach($columns as $column)
                                <th class="hidden_th">{{ $column->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php ($i = 1)
                        @foreach($sales_products as $sale_product)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $sale_product->sale_date }}</td>
                                <td>{{ $sale_product->client_name }}</td>
                                <td>{{ $sale_product->invoice_code }}</td>
                                <td>{{ $sale_product->product_name.'( '.$sale_product->packet_name.'('.$sale_product->packet_quantity.')  '.$sale_product->unit_name.'('.$sale_product->unit_quantity.') )' }}</td>
                                <td>{{ $sale_product->quantity }}</td>
                                <td>{{ $sale_product->price }}</td>
                                <td>{{ $sale_product->price * $sale_product->quantity }}</td>
                                <td>{{ $sale_product->price * $sale_product->quantity }}</td>

                                @php
                                    $all_vat_taxs = explode(',', $sale_product->new_vat_tax);
                                    $vat_tax_ids = explode(',', $sale_product->vat_tax_id);
                                    $vat_tax_names = explode(',', $sale_product->vat_tax_name);
                                    foreach ($columns as $column) {
                                        if(in_array($column->id, $vat_tax_ids)){
                                            echo "<td class='hidden_th'>".$all_vat_taxs[array_search($column->id, $vat_tax_ids)]."</td>";
                                        }
                                        else{
                                            echo "<td class='hidden_th'>0</td>";
                                        }
                                    }
                                @endphp
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".myselect").select2();
</script>
@stop
