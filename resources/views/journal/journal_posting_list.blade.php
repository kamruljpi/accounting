@extends('layouts.dashboard')
@section('page_heading','Journal Posting List')
@section('section')

<style type="text/css">
    .panel-heading{
        display: none;
    }
    .panel-body{
        padding: 0px;
    }
</style>

@if(Session::has('journal_posting_status'))
    @include('widgets.alert', array('class'=>'success', 'message'=> Session::get('journal_posting_status') ))
@endif


<div class="create_form_btn" style="margin-bottom: 20px;">
    <a href="{{ Route('journal_posting_form_view') }}">
        <button class="btn btn-success">{{ trans('others.add_journal_posting_label') }}</button>
    </a>
</div>


<div class="col-sm-12">
    <div>


        @section ('cotable_panel_body')

            @if(Session::has('role_delete_msg'))
                @include('widgets.alert', array('class'=>'danger', 'message'=> Session::get('role_delete_msg') ))
            @endif
            @if(Session::has('role_update_msg'))
                @include('widgets.alert', array('class'=>'success', 'message'=> Session::get('role_update_msg') ))
            @endif
            @if(Session::has('company_delete'))
                @include('widgets.alert', array('class'=>'danger', 'message'=> Session::get('company_delete') ))
            @endif


            <div class="text-center">
                {{ $get_all_journal->links() }}
            </div>

           <!--  <div class="input-group add-on">
              <input class="form-control" placeholder="{{trans('others.search_placeholder')}}" name="srch-term" id="user_search" type="text">
              <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
              </div>
            </div> -->
            <br>


            <div class="input-group">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ trans('others.serial_no_label') }}</th>
                        <th>{{ trans('others.mxp_date') }}</th>
                        <th>{{ trans('others.mxp_particulars') }}</th>
                        <th>{{ trans('others.mxp_credit') }}</th>
                        <th>{{ trans('others.mxp_debit') }}</th>
                        <th>{{ trans('others.status_label') }}</th>
{{--                        <th>{{ trans('others.action_label') }}</th>--}}
                    </tr>
                </thead>
                <tbody>
                    @php ($i = 1)
                    @foreach($get_all_journal as $all_list)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$all_list->journal_date}}</td>
                        <td>{{$all_list->particular}}</td>
                        <td>{{$all_list->transaction_amount_credit}}</td>
                        <td>{{$all_list->transaction_amount_debit}}</td>
                        <td>{{$all_list->is_active}}</td>
                        {{--<td>--}}

                            {{--<a href="#"> @include('widgets.button', array('class'=>'success', 'value'=>trans('others.view_details_button')))</a>--}}

                        {{--</td>--}}
                    </tr>
                     @endforeach
                </tbody>
            </table>
            </div>
        @endsection
        @include('widgets.panel', array('header'=>true, 'as'=>'cotable'))
    </div>
</div>

@stop
