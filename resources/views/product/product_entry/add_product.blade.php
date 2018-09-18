@extends('layouts.dashboard')
@section('page_heading',trans('others.heading_add_new_packet_label'))
@section('section')

@section('section')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('others.add_packet_label') }}</div>
                    <div class="panel-body">

                        @if(isset($name))
                            @include('widgets.alert', array('class'=>'success', 'dismissable'=>true, 'message'=> $name." is assigned", 'icon'=> 'check'))
                        @endif
                        
                        <form class="form-horizontal" role="form" method="POST" action="{{ Route('add_product_action') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif


                            <div class="form-group">
                                <label class="col-md-4 control-label">{{ trans('others.product_group_label') }}</label>
                                <div class="col-md-6">
                                    <select class="form-control input_required" name="p_group" required >
                                        @foreach($productGroups as $productGroup)
                                            <option value="{{ $productGroup->id }}">{{ $productGroup->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">{{ trans('others.product_name_label') }}</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control  input_required" name="p_name" value="{{ old('p_name') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">{{ trans('others.packet_details_label') }}</label>
                                <div class="col-md-6">
                                    <select style="width:100%" name="packet_ids[]" class="selections " multiple="multiple">
                                        @foreach($packets as $packet)
                                            <option value="{{ $packet->id }}" placeholder="Select packet" required>{{ $packet->name.'('.$packet->quantity.')    '.$packet->unit_name.'('.$packet->unit_quantity.')' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">{{ trans('others.product_code_label') }}</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control  input_required" name="p_code" value="{{ old('p_code') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3 col-md-offset-4">
                                    <div class="select">
                                        <select class="form-control" type="select" name="is_active" >
                                            <option  value="1" name="is_active" >{{ trans('others.action_active_label') }}</option>
                                            <option value="0" name="is_active" >{{ trans('others.action_inactive_label') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary" style="margin-right: 15px;">
                                        {{ trans('others.save_button') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(".selections").select2();
    </script>
@endsection

