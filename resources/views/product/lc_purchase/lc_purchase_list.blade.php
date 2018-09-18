@extends('layouts.dashboard')
@section('page_heading','LC Purchased List')
@section('section')

<style type="text/css">
    .table{
            margin-bottom: 5px;
    }
</style>

<div class="col-sm-12" id="demo">
    <div class="row">
        <div class="col-sm-12">
            {{-- @if(count($errors) > 0)
                    <div class="alert alert-danger" role="alert">
                        @foreach($errors->all() as $error)
                          <li><span>{{ $error }}</span></li>
                        @endforeach
                    </div>
            @endif --}}

            @if(Session::has('purchase_table'))
                @include('widgets.alert', array('class'=>'success', 'message'=> Session::get('purchase_table') ))
            @endif

            <form role="form" action="{{ Route('lc_product_purchase_search_list') }}" method="post">

                <input type="hidden" name="_token" value="{{ csrf_token() }}" required>

                <div class="form-group input_table_specific">

                    <style type="text/css">
                        input{
                            width: 100% !important;
                        }
                    </style>

                    @php
                        $searchMore = false;
                        if (Session::has('lcAmountUsd') || Session::has('client') || Session::has('product_name') || Session::has('product_code') || Session::has('bank_name') || Session::has('country_orign') || Session::has('lc_no')){
                            $searchMore = true;
                        }
                    @endphp

                    <div class="row">
                        <div class="col-md-4 row">
                            <div class="col-md-4" style="font-size: 15px;">Form date: </div>
                            <div class="col-md-8">
                                <input type="date" class="form-control" placeholder="Search date...." name="dateSearchFldFrom" id="dateSearchFldFrom" value="{{ Session::get('dateSearchFldFrom') }}">
                            <p id="search"></p>
                            </div>
                        </div>

                        <div class="col-md-4 row">
                            <div class="col-md-4" style="font-size: 15px">To date: </div>
                            <div class="col-md-8">
                                <input type="date" class="form-control" placeholder="Search date...." name="dateSearchFldTo" id="dateSearchFldTo" value="{{ Session::get('dateSearchFldTo') }}">
                            <p id="search"></p>
                           </div>
                        </div>

                        <div class="col-md-2">
                            <input class="form-control btn btn-primary" type="submit" value="View result" >
                        </div>

                       {{-- 
                        <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="L/C amount(usd)" name="lcAmountUsd" id="lcAmountUsd" value="{{ Session::get('lcAmountUsd') }}">
                            <p id="search"></p>
                        </div> --}}
                    </div>

                    <div class="row">
                        
                        <div class="col-md-2 col-md-offset-4" style="padding-bottom: 10px;">
                            <input class="form-control btn btn-info" type="submit" value="Search more" id="lc_search_with_others_field">
                        </div>

                        <div class="col-md-2">
                            <input class="form-control btn btn-danger" type="submit" value="cancel" id="lc_search_with_others_field_close" style="display: {{ ($searchMore == true)? 'block':'none'}};">
                        </div>
                    </div>

                    <div class="row" style="display: {{ ($searchMore == true)? 'block':'none'}};" id="lc_others_seach_field_box">
                        
                        <div class="col-md-2">
                            <select name="client" class="myselect form-control">
                                <option value="">Client name</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->chart_o_acc_head_id }}" {{ ((Session::get('client')) == $client->chart_o_acc_head_id)? "selected":"" }}>{{ $client->acc_final_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="product_name" class="myselect form-control">
                                <option value="">Product name</option>
                                @foreach($select_products_in_search as $select_product_in_search)
                                    <option value="{{ $select_product_in_search->id }}" {{ ((Session::get('product_name')) == $select_product_in_search->id)? "selected":"" }}>{{ $select_product_in_search->name.'('.$select_product_in_search->packet_name.')' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="product_code" class="myselect form-control">
                                <option value="">Product code</option>
                                @foreach($select_products_in_search as $select_product_in_search)
                                    <option value="{{ $select_product_in_search->id }}" {{ ((Session::get('product_code')) == $select_product_in_search->id)? "selected":"" }}>{{ $select_product_in_search->product_code }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="lc_no" class="myselect form-control">
                                <option value="">L/C no./id</option>
                                @foreach($lc_nos_in_search as $lc_no)
                                    <option value="{{ $lc_no->lc_no }}" {{ ((Session::get('lc_no')) == $lc_no->lc_no)? "selected":"" }}>{{ $lc_no->lc_no }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="bank_name" class="myselect form-control">
                                <option value="">Bank name</option>
                                @foreach($bank_names_in_search as $bank_name)
                                    <option value="{{ $bank_name->bank_name }}" {{ ((Session::get('bank_name')) == $bank_name->bank_name)? "selected":"" }}>{{ $bank_name->bank_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="country_orign" class="myselect form-control">
                                <option value="">Contry of origin</option>
                                @foreach($country_origns_in_search as $country_orign)
                                    <option value="{{ $country_orign->country_orign }}" {{ ((Session::get('country_orign')) == $country_orign->country_orign)? "selected":"" }}>{{ $country_orign->country_orign }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <?php if(Session::get('dateSearchFldFrom')>0 && Session::get('dateSearchFldTo')>0){ ?>
                    <div id="print_page" class="right-aligned-texts pull-right" style="display: block;">
                        <div class="btn-group">
                            <button class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bars"></i> Export Table Data</button>
                            <ul class="dropdown-menu " role="menu">
                                <li><a href="{{ route('lc_p_export_action',['dateSearchFldFrom'=>Session::get('dateSearchFldFrom'),'dateSearchFldTo'=>Session::get('dateSearchFldTo'),'type'=>'xls']) }}"><i class="fa fa-file-excel-o"></i>&nbsp;Excel</a></li>
                                <li></i><a href="{{ route('lc_p_export_action',['dateSearchFldFrom'=>Session::get('dateSearchFldFrom'),'dateSearchFldTo'=>Session::get('dateSearchFldTo'),'type'=>'csv']) }}"><i class="fa fa-file-text-o">&nbsp;</i>csv</a></li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
{{-- 
                {{ Session::forget('dateSearchFldFrom') }}
                {{ Session::forget('dateSearchFldTo') }} --}}
                {{ Session::forget('lcAmountUsd') }}
                {{ Session::forget('client') }}
                {{ Session::forget('product_name') }}
                {{ Session::forget('product_code') }}
                {{ Session::forget('bank_name') }}
                {{ Session::forget('country_orign') }}
                {{ Session::forget('lc_no') }}
            </form>

            <form role="form" action="{{ Route('product_purchase_add') }}" method="post">

                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <table class="table table-bordered purchase_table" id="tblSearch">
                    <thead>
                        <tr style="background-color:#f9f9f9;">
                            <th class="">Serial</th>
                            <th class="">LC No</th>
                            <th class="">LC opening date</th>
                            <th class="">LC Value(taka)</th>
                            <th class="">Others Cost</th>
                            <th class="">Margin Paid</th>
                            <th class="">Total Cost(include others cost)</th>
                            <th class="">Total Cost(exclude others cost)</th>
                            <th class="">Status</th>
                            <th class="">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php ($i = 1) 
                            @foreach($products_table as $product_table)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $product_table->lc_no }}</td>
                                    <td>{{ $product_table->purchase_date }}</td>
                                    <td>{{ $product_table->lc_amount_taka }}</td>
                                    <td>{{ $product_table->others_cost }}</td>
                                    <td>{{ $product_table->lc_margin }}</td>
                                    <td>{{ $product_table->total_amount_with_other_cost }}</td>
                                    <td>{{ $product_table->total_amount_without_other_cost }}</td>
                                    
                                    <td>
                                        @if ($product_table->lc_status == 1)
                                           <!--  <a href="#"> @include('widgets.button', array('class'=>'success', 'value'=>'approved')) </a> -->
                                           <span class="text-success">approved</span>
                                        @else
                                            <span class="text-danger">pending</span>
                                        @endif
                                    </td>
                                    <td style="width:160px;">
                                        <a name="lc_purchase_summary_btn" data-index="{{ $product_table->lc_purchase_id }}"> @include('widgets.button', array('class'=>'info btn', 'value'=>'details')) </a>
                                        @if ($product_table->lc_status == 0) 
                                        <a name="lc_purchase_table_update_btn" data-index="{{ $product_table->lc_purchase_id }}"> @include('widgets.button', array('class'=>'info btn', 'value'=>'update')) </a>
                                        @endif
                                    </td>

                                    <div class="lc_purchase_product_view_modal">
                                    </div>

                                </tr>
                            @endforeach
                        </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".myselect").select2();
</script>
@stop
