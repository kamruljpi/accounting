@extends('layouts.dashboard')
@section('page_heading',trans('others.heading_sub_accounts_head_list_label'))
@section('section')

@section('section')
	<div class="col-md-10 col-md-offset-1">
		<div>
			<a href="{{ Route('add_sub_accounts_head_view') }}"> @include('widgets.button', array('class'=>'success', 'value'=>'Add Account sub head'))</a><br><br>
		</div>

		@include('layouts.pagination',['route_name'=>'accounts_sub_head_name_view'])

		<div>
			<table class="table table-bordered">
	            <thead>
	                <tr>
						<th>{{trans('others.serial_no_label')}}</th>
						<th>Acc sub head name/type</th>
						<th>Acc sub head name/type</th>
						<th>{{trans('others.status_label')}}</th>
						<th>{{trans('others.action_label')}} </th>
	                </tr>
	            </thead>
	            <tbody>
	            @php ($i = 1) 
		            @foreach($sub_accounts_heads as $sub_accounts_head)
						<tr>
							<td>{{ $i++ }}</td>
							<td>{{ $sub_accounts_head->account_head_details }}</td>
							<td>{{ $sub_accounts_head->sub_head }}</td>
							<td>{{ ($sub_accounts_head->is_active == 1)? trans('others.action_active_label'):trans('others.action_inactive_label') }}</td>
							<td> 
								<a href="{{ Route('edit_sub_accounts_head_view') }}/{{ $sub_accounts_head->accounts_sub_heads_id }}"> @include('widgets.button', array('class'=>'success', 'value'=>trans('others.edit_button'))) </a>
								<a class="delete_id" href="{{ Route('delete_sub_accounts_head_action') }}/{{ $sub_accounts_head->accounts_sub_heads_id }}"> @include('widgets.button', array('class'=>'danger', 'value'=>trans('others.delete_button'))) </a>
							</td>
						</tr>
					@endforeach
	            </tbody>
	        </table>
		</div>
	</div>
@endsection


