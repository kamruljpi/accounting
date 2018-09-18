@extends('layouts.dashboard')
{{-- @section('page_heading','Add Role') --}}
@section('section')
           
<div class="col-sm-12 add_packet" id="demo">
    <div class="row">
        

        <div class="col-sm-6 col-sm-offset-2">
            @if(count($errors) > 0)
                    <div class="alert alert-danger" role="alert">
                        @foreach($errors->all() as $error)
                          <li><span>{{ $error }}</span></li>
                        @endforeach
                    </div>
            @endif

            @if(Session::has('new_packet_updated'))
                @include('widgets.alert', array('class'=>'success', 'message'=> Session::get('new_packet_updated') ))
            @endif
            @if(Session::has('packet_deleted'))
                @include('widgets.alert', array('class'=>'success', 'message'=> Session::get('packet_deleted') ))
            @endif

            <form role="form" action="{{ Route('update_packet_action') }}/{{ $request->pid }}" method="post">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-group">
                        <label>{{ trans('others.packet_name_label') }}</label>
                        <input class="form-control input_required" type="text" name="name" value="{{ $packet->name }}">
                    </div>
                    <div class="form-group">
                        <label>{{ trans('others.quantity_label') }}</label>
                        <input class="form-control input_required" type="text" name="quantity" value="{{ $packet->quantity }}">
                    </div>
                    <div class="form-group">
                        <label>{{ trans('others.unit_quantity_label') }}</label>
                        <input class="form-control input_required" type="text" name="unit_quantity" value="{{ $packet->unit_quantity }}">
                    </div>

                    <div class="form-group">
                        <label>{{ trans('others.option_select_unit_label') }}</label>
                        <select name="unit_id" class="form-control input_required">
                            <option value="">{{ trans('others.option_select_unit_label') }}</option>
                            
                            @foreach($units as $unit)
                                <option
                                    @if($packet->unit_id == $unit->id)
                                        selected
                                    @endif
                                 value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach

                        </select>
                    </div>
                    
                    
                    <div class="form-group">
                    <label>{{ trans('others.status_label') }}</label>
                        <select class="form-control input_required" name="is_active">
                            
                            <option value="0">{{ trans('others.action_inactive_label') }}</option>
                            <option @if($packet->is_active == 1) {{ 'selected' }} @endif value="1">{{ trans('others.action_active_label') }}</option>
                        </select>
                    </div>
                    

                     <div class="form-group" style="float: right; width: 60%; margin-right: 10%">
                        <input style="float: left; width: 100% !important" class="form-control btn btn-primary btn-outline" type="submit" value="{{ trans('others.update_packet_button') }}" >
                    </div>
            </form>
        </div>
    </div>
</div>
            
@stop
