@extends('layouts.dashboard')
@section('page_heading','Purchased Products List')
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

            <form role="form" action="{{ Route('product_purchase_search_list') }}" method="post">

                <input type="hidden" name="_token" value="{{ csrf_token() }}" required>

                <div class="form-group row">

                    {{-- <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Search client...." name="clientSearchFld" id="clientSearchFld">
                        <input type="hidden" name="clientSearchHiden" id="clientSearchHiden">
                        <div id="clientSearchDiv" class="list-group"></div>
                        <p id="search"></p>
                    </span>
                    </div> --}}
                    <style type="text/css">
                        input{
                            width: 100% !important;
                        }
                    </style>

                    <div class="col-md-2">
                        <select name="client_id" class="myselect">
                            <option value="">Select Client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->chart_o_acc_head_id }}" {{ ((Session::get('client_id_for_select_field')) == $client->chart_o_acc_head_id)? "selected":"" }}>{{ $client->acc_final_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="invoice_id" class="myselect">
                            <option value="">Select Invoice</option>
                            @foreach($invoice_codes as $invoice_code)
                                <option value="{{ $invoice_code->id }}" {{ ((Session::get('invoice_id_for_select_field')) == $invoice_code->id)? "selected":"" }}>{{ $invoice_code->invoice_code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="stock_status" class="form-control">
                            <option value="" name="stock_status">Select stock</option>
                            <option value=1 {{ ((Session::get('stock_status')) == '1')? "selected":"" }} name="stock_status">Stocked</option>
                            <option value=0 {{ ((Session::get('stock_status')) == '0')? "selected":"" }} name="stock_status">Not yet stocked</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <input type="date" class="form-control" placeholder="Search date...." name="dateSearchFldFrom" id="dateSearchFldFrom" value="{{ Session::get('fromDate')}}" >
                        <p id="search"></p>
                    </span>
                    </div>

                    <div class="col-md-2">
                        <input type="date" class="form-control" placeholder="Search date...." name="dateSearchFldTo" id="dateSearchFldTo" value="{{ Session::get('toDate') }}">
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

            @if($currentPage > 0)
                @include('layouts.pagination',['route_name'=>'product_purchase_list_view'])
            @endif

            @if(Route::currentRouteName() != 'product_purchase_list_view')
            <form role="form" action="{{ Route('product_purchase_add') }}" method="post">

                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <table class="table table-bordered purchase_table" id="tblSearch" >
                    <thead>
                        <tr>
                            <th class="">Serial</th>
                            <th class="">Product Name</th>
                            <th class="">Client</th>
                            <th class="">Quantity</th>
                            <th class="">Price/unit</th>

                            @foreach($columns as $column)
                                <th class="">{{ $column->name }}</th>
                            @endforeach

                            <th class="">Total Amount</th>
                            <th class="">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php ($i = 1)
                        @foreach($purchase_products as $purchase_product)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $purchase_product->product_name.'( '.$purchase_product->packet_name.'('.$purchase_product->packet_quantity.')  '.$purchase_product->unit_name.'('.$purchase_product->unit_quantity.') )' }}</td>
                                <td>{{ $purchase_product->client_name }}</td>
                                <td>{{ $purchase_product->quantity }}</td>
                                <td>{{ $purchase_product->price }}</td>

                                @php
                                    $all_vat_taxs = explode(',', $purchase_product->new_vat_tax);
                                    $vat_tax_ids = explode(',', $purchase_product->vat_tax_id);
                                    $vat_tax_names = explode(',', $purchase_product->vat_tax_name);
                                    foreach ($columns as $column) {
                                        if(in_array($column->id, $vat_tax_ids)){
                                            echo "<td>".$all_vat_taxs[array_search($column->id, $vat_tax_ids)]."
                                            </td>";
                                        }
                                        else{
                                            echo "<td>0</td>";
                                        }
                                    }
                                @endphp

                                <td>{{ $purchase_product->total_amount_with_vat }}</td>
                                <td>
                                    <a name="purchase_product_details_modal_view" data-index="{{ $purchase_product->id }}"> @include('widgets.button', array('class'=>'info', 'value'=>trans('others.view_btn'))) </a>
                                    <a name="purchase_product_update_modal_view" data-index="{{ $purchase_product->id }}"> @include('widgets.button', array('class'=>'primary', 'value'=>trans('others.edit_btn'))) </a>
                                    <div class="productViewModal"></div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
            @endif
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".myselect").select2();
</script>
@stop
