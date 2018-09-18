@extends('layouts.dashboard')
@section('page_heading',trans('others.heading_accounts_head_list_label'))
@section('section')

@section('section')
	<div class="col-md-10 col-md-offset-1">
		<div>
			<a href="{{ Route('add_accounts_head_view') }}"> @include('widgets.button', array('class'=>'success', 'value'=>'Add Account head'))</a><br><br>
		</div>

		<div class="text-center">
			{{ $accounts_heads->links() }}
		</div>
		<div>
			<table class="table table-bordered">
	            <thead>
	                <tr>
						<th>{{trans('others.serial_no_label')}}</th>
						<th>Account head name/type</th>
						<th>code</th>
						<th>{{trans('others.status_label')}}</th>
						<th>{{trans('others.action_label')}} </th>
	                </tr>
	            </thead>
	            <tbody>
	            @php ($i = 1) 
		            @foreach($accounts_heads as $accounts_head)
						<tr>
							<td>{{ $i++ }}</td>
							<td>{{ $accounts_head->head_name_type }}</td>
							<td>{{ $accounts_head->account_code }}</td>
							<td>{{ ($accounts_head->is_active == 1)? trans('others.action_active_label'):trans('others.action_inactive_label') }}</td>
							<td> 
								<a href="{{ Route('edit_accounts_head_view') }}/{{ $accounts_head->accounts_heads_id }}"> @include('widgets.button', array('class'=>'success', 'value'=>trans('others.edit_button'))) </a>
								<a class="delete_id" href="{{ Route('delete_accounts_head_action') }}/{{ $accounts_head->accounts_heads_id }}"> @include('widgets.button', array('class'=>'danger', 'value'=>trans('others.delete_button'))) </a>
							</td>
						</tr>
					@endforeach
	            </tbody>
	        </table>
		</div>
	</div>
@endsection
