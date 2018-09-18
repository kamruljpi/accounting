@extends('layouts.dashboard')
@section('page_heading','Sale Return List')
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


            <form class="form-horizontal" action="{{ Route('sales_return_get') }}" method="post" id="chalanForm">

                <input type="hidden" name="_token" value="{{ csrf_token() }}" required>

                <div class="form-group row">
                    <style type="text/css">
                        input{
                            width: 100% !important;
                        }
                    </style>

                    <div class="col-md-3">
                        <select name="order_no" class="myselect" required>
                            <option value="">Select Order No</option>
                            @foreach($get_oreder_no as $order)
                            <option value="{{$order->order_no}}">{{$order->order_no}}</option>
                            @endforeach 
                        </select>   
                    </div>

                    <div class="col-md-7 row">
                    </div>
                    <div class="col-md-2">
                        <input class="form-control btn btn-primary" type="submit" value="{{ trans('others.search_btn') }}" id="salereturnSearchBtn" >
                    </div>
                </div>
            </form>
        </div>

        <div class="preLoadImageView" style="display: none;">
            <center>
                <img src="{{ asset("assets/images/preLoader_2.gif") }}">
            </center>
        </div>
        <div class="chalanDetailsView"></div>
        <div class="dataNotFoundMessage" style="margin-top: 10%; margin-left: 9%;"></div>
        
        <div class="saleDataView" style="display: block;">
           <form method="post" action="{{Route('sales_return_store')}}" id="frm_value" enctype="multipart/form-data">
             
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Sale Date</th>
                        <th>Particular Name</th>
                        <th>Transport Date</th>
                        <th>Invoice No</th>
                        <th>Client</th>
                        <th>Quantity</th>
                        <th>Price/Unit</th>
                        <th>Due</th>
                        <th>Bonus</th>
                        <th>Total Price</th>
                        <th>Chart of Account</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                     @if($slreturn)
            @foreach($slreturn as $val)
          <input type="hidden" name="_token" value="{{ csrf_token() }}" required>
                    <tr>
                        <td>{{$val->order_no}}</td>
                        <td>{{$val->sale_date}}</td>
                        <td>
                            {{$val->p_name}}{{$val->pkt_name}}({{$val->unit_quantity}}){{$val->u_name}}({{$val->unit_quantity}})
                       <input type="hidden" id="" name="product_id[]"
                            value="{{$val->product_id}}" />
                            <input type="hidden" id="" name="product_name[]"
                            value="{{$val->p_name}}{{$val->pkt_name}}({{$val->unit_quantity}}){{$val->u_name}}({{$val->unit_quantity}})" />
                        </td>
                        <td>{{$val->transport_date}}</td>
                        <td>{{$val->invoice_code}}</td>
                        <td>{{$val->acc_final_name}}
                        <input type="hidden" name="jurnalParticular[]"
                            value="{{$val->acc_final_name}}" /></td>
                        <td>{{$val->slquantity}}
                         <input type="hidden" name="slquantity[]"
                            value="{{$val->slquantity}}" /></td>
                        <td>{{$val->price_per_unit}}
                        <input type="hidden" name="price_per_unit[]"
                            value="{{$val->price_per_unit}}" />
                        </td>
                        <td>{{$val->due_ammount}}
                        <input type="hidden" name="due_ammount[]"
                            value="{{$val->due_ammount}}" /></td>
                        <td>{{$val->bonus}}
                            <input type="hidden" name="bonus[]"
                            value="{{$val->bonus}}" /></td>
                        <td>{{$val->total_amount}}
                            <input type="hidden" name="total_ammount[]"
                            value="{{$val->total_amount}}" />
                        </td>
                        <td>
                            <select class="chart_acc" name="chart_of_acc[]" id="chart_of_acc">
                                <option value="">-- Select --</option>
                                @foreach($chartOfAccs as $chartOfAcc)
                                    <option value="{{ $chartOfAcc->chart_o_acc_head_id }}"> {{ $chartOfAcc->acc_final_name }} </option>
                                @endforeach
                            </select>
                        </td>
                        <input type="hidden" name="client_id[]" value="{{$val->client_id}}">
                        <td><input type="checkbox" id="returs_sale_add" name="lot_id[]"
                            value="{{$val->lot_id}}" />
                        </td>
                    </tr>
                    @endforeach  
          @endif
                </tbody>
            </table>
           <div class="col-md-2">
                <a href=""><input class="form-control btn btn-primary" type="submit" value="submit" id="returnsave"></a>
            </div>
            </form>
        </div>

    </div>   
</div>
<script type="text/javascript">
    $(".myselect").select2();
</script>
@stop
