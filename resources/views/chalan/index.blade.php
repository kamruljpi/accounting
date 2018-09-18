@extends('layouts.dashboard')
@section('page_heading','Challan')
@section('section')

<style type="text/css">
    .table{
            margin-bottom: 5px;
    }
</style>

<div class="col-sm-12" id="demo">
    <div class="row">
        <div class="col-sm-12 input_table_specific">
            @if(count($errors) > 0)
                    <div class="alert alert-danger" role="alert">
                        @foreach($errors->all() as $error)
                          <li><span>{{ $error }}</span></li>
                        @endforeach
                    </div>
            @endif

      

            <form class="form-horizontal" method="post" id="GenerateForm">

                <input type="hidden" name="_token" value="{{ csrf_token() }}" required>

                <div class="form-group row">
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
                        <select name="order_id" class="myselect">
                            <option value="">Order No</option>
                            @foreach($orderNo as $OrderNo)
                                <option value="{{ $OrderNo->order_no }}" >{{ $OrderNo->order_no }}</option>
                            @endforeach
                        </select>
                    </div>
                   
                    <div class="col-md-2">
                        <input class="form-control btn btn-primary btn-outline" type="submit" value="{{ trans('others.genarate') }}" id="chalanGenagareBtn">
                    </div>
                </div>
            </form>
        </div>
<div class="preLoad" style="display: none;">
            <center>
                <img src="{{ asset("assets/images/preLoader_2.gif") }}">
            </center>
        </div>
        <div class="kol" style="display:none;">
          <!--  @include('chalan.generated-chalan') -->
        </div>
        

        
    </div>
</div>

<script type="text/javascript" src='{{ asset("js/ledger.js") }}'></script>
<script type="text/javascript">
    $(".myselect").select2();
</script>

@stop

