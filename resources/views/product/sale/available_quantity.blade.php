@extends('layouts.dashboard')
@section('page_heading',trans('others.heading_report_label'))
@section('section')

<style type="text/css">
    .panel-heading{
        display: none;
    }
    .panel-body{
        padding: 0px;
    }
    .search_box{
        width: 50% !important;
        float: left;
    }
    .search_box select{
        padding: 10px;
    }
</style>

@if(Session::has('vat_tax_message'))
    @include('widgets.alert', array('class'=>'success', 'message'=> Session::get('vat_tax_message') ))
@endif


            <div class="col-md-4 ">
                <div class="search_box">
                    <select name="a_q_s" class="myselect" id="available_quantity_s">
                        <option value="">{{ trans('others.option_select_product_label') }}</option>
                        @foreach($available_quantity as $item)
                            <option value="{{ $item->product_name.'( '.$item->packet_name.'('.$item->packet_quantity.')  '.$item->unit_name.'('.$item->unit_quantity.') )' }}">
                            {{ $item->product_name.'( '.$item->packet_name.'('.$item->packet_quantity.')  '.$item->unit_name.'('.$item->unit_quantity.') )' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="search_box">
                    <button class="search_available_q form-control btn btn-primary btn-outline">{{ trans('others.search_placeholder') }}</button>
                </div>
            </div>

<div class="col-sm-12">




    <div class="row">

            <br>

            @section ('cotable_panel_body')

            @if(Session::has('packet_delete_msg'))
                @include('widgets.alert', array('class'=>'danger', 'message'=> Session::get('packet_delete_msg') ))
            @endif
            @if(Session::has('packet_update_msg'))
                @include('widgets.alert', array('class'=>'success', 'message'=> Session::get('packet_update_msg') ))
            @endif








            <table class="table table-bordered" id="tblSearch">
                <thead>
                    <tr>
                        <th class="">{{ trans('others.serial_no_label') }}</th>
                        <th class="">date</th>
                        <th class="">{{ trans('others.product_name_label') }}</th>
                        {{-- <th class="">Calculation Type</th> --}}
                        <th class="">{{ trans('others.product_group_label') }}</th>
                        <th class="">{{ trans('others.total_quantity_label') }}</th>
                        <th class="">{{ trans('others.available_quantity_label') }}</th>
                         <th class="">{{ trans('others.unit_label') }}</th>
                        <th class="">{{ trans('others.sale_quantity_label') }}</th>
                        <th class="">avg cost/unit</th>
                        <th class="">sales/unit</th>
                    </tr>
                </thead>
                <tbody>

                    <?php $i = 1;?>
                    @foreach($available_quantity as $item)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $item->created_at}}</td>
                                <td>{{ $item->product_name.'( '.$item->packet_name.'('.$item->packet_quantity.')  '.$item->unit_name.'('.$item->unit_quantity.') )' }}</td>
                                <td>{{ $item->group_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->available_quantity }}</td>
                                <td>{{ $item->unit_name }}</td>
                                <td>{{ $item->sale_quantity }}</td>
                                <td>{{ $item->avg_price_unit }}</td>
                                <td>{{ $item->sale_price }}</td>

                            </tr>
                    @endforeach


                </tbody>
            </table>
            @endsection
            @include('widgets.panel', array('header'=>true, 'as'=>'cotable'))
        {{-- </div> --}}
    </div>
</div>
<script type="text/javascript">
    $(".myselect").select2();
</script>
@stop

