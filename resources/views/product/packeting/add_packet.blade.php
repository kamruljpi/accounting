@extends('layouts.dashboard')
{{-- @section('page_heading','Add Role') --}}
@section('section')
           
<div class="col-sm-12 add_packet" id="demo">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-2   ">
            @if(count($errors) > 0)
                    <div class="alert alert-danger" role="alert">
                        @foreach($errors->all() as $error)
                          <li><span>{{ $error }}</span></li>
                        @endforeach
                    </div>
            @endif

            <form role="form" action="{{ Route('add_packet_action') }}" method="post">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-group">
                        <label>{{ trans('others.packet_name_label') }}</label>
                        <input class="form-control input_required" type="text" name="name" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ trans('others.quantity_label') }}</label>
                        <input class="form-control input_required" type="text" name="quantity" value="{{ old('quantity') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ trans('others.unit_quantity_label') }}</label>
                        <input class="form-control " type="text" name="unit_quantity" value="{{ old('unit_quantity') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ trans('others.option_select_unit_label') }}</label>
                        <select name="unit_id" class="form-control input_required">
                            <option value="">{{ trans('others.option_select_unit_label') }}</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('others.status_label') }}</label>
                        <select name="is_active" class="form-control input_required">
                            <option value="1">{{ trans('others.action_active_label') }}</option>
                            <option value="0">{{ trans('others.action_inactive_label') }}</option>
                        </select>
                    </div>
                    
                    <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">
                    <input type="hidden" name="group_id" value="{{ $request->session()->get('group_id') }}">
                    <input type="hidden" name="user_id" value="{{ Auth::user()->user_id }}">
                    
    
                    <div class="form-group" style="float: right; width: 60%; margin-right: 10%">
                        <input style="float: left; width: 100% !important" class="form-control btn btn-primary btn-outline" type="submit" value="{{ trans('others.add_packet_button') }}" >
                    </div>
            </form>
        </div>
    </div>
</div>
            
@stop
