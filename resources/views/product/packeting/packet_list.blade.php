@extends('layouts.dashboard')
@section('page_heading',trans('others.heading_packet_list'))
@section('section')

<style type="text/css">
    .panel-heading{
        display: none;
    }
    .panel-body{
        padding: 0px;
    }
</style>

@if(Session::has('new_packet_added'))
    @include('widgets.alert', array('class'=>'success', 'message'=> Session::get('new_packet_added') ))
@endif


<div class="create_form_btn" style="margin-bottom: 20px;">
    <a href="{{ Route('add_packet_view') }}">
        <button class="btn btn-success">{{ trans('others.add_packet_button') }}</button>
    </a>
</div>


<div class="col-sm-12">
    <div class="row">
        
            <div class="input-group add-on">
              <input class="form-control" placeholder="{{trans('others.search_placeholder')}}" name="srch-term" id="user_search" type="text">
              <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
              </div>
            </div>
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
                        <th class="">{{ trans('others.packet_name_label') }}</th>
                        <th class="">{{ trans('others.quantity_label') }}</th>
                        <th class="">{{ trans('others.unit_quantity_label') }}</th>
                        <th class="">{{ trans('others.unit_label') }}</th>
                        <th class="">{{ trans('others.status_label') }}</th>
                        <th class="">{{ trans('others.action_label') }}</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php $i=1;  ?>
                    @foreach($packetList as $packet)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $packet->name }}</td>
                                <td>{{ $packet->quantity }}</td>
                                <td>{{ $packet->unit_quantity }}</td>
                                <td>
                                    @foreach($units as $unit)
                                        @if($packet->unit_id == $unit->id)
                                                {{ $unit->name }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @if($packet->is_active == '1')
                                        {{ trans('others.action_active_label') }}
                                    @else
                                        {{ trans('others.action_inactive_label') }}
                                    @endif
                                </td>
                                
                                <td>
                                    <a href="{{ Route('update_packet_view') }}/{{ $packet->id }}"> @include('widgets.button', array('class'=>'success', 'value'=>trans('others.edit_button'))) </a>
                                    
                                    <a class="delete_id" href="{{ Route('delete_packet_action') }}/{{ $packet->id }}" > @include('widgets.button', array('class'=>'danger', 'value'=>trans('others.delete_button'))) </a>
                                </td>
                            </tr>    
                    @endforeach 
                    
                    
                </tbody>
            </table>    
            @endsection
            @include('widgets.panel', array('header'=>true, 'as'=>'cotable'))
        {{-- </div> --}}
    </div>
</div>
            
@stop
