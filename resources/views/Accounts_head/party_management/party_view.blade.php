@extends('layouts.dashboard')
@section('page_heading', 'Party' )
@section('section')

@section('section')
	<div class="col-md-10 col-md-offset-1">
		<div>
			<a href="{{ Route('add_new_party') }}"> @include('widgets.button', array('class'=>'success', 'value'=>'Add party'))</a><br><br>
		</div>

		<div>
			<table class="table table-bordered">
	            <thead>
	                <tr>
						<th>{{trans('others.serial_no_label')}}</th>
						<th>Account head/type</th>
						<th>Account sub head/type</th>
						<th>Account head class name</th>
						<th>Account sub head class name</th>
						<th>Chart of Account name</th>
						<th>{{trans('others.status_label')}}</th>
						<th>{{trans('others.action_label')}}</th>
		                </tr>
	            </thead>
	            <tbody>
	            	@php ($i = 1) 
						 @foreach($chart_of_accs as $chart_of_acc)
						<tr>
							<td>{{ $i++ }}</td>
							<td>{{ $chart_of_acc->head_name_type }}</td>
							<td>{{ $chart_of_acc->sub_head }}</td>
							<td>{{ $chart_of_acc->head_class_name }}</td>
							<td>{{ $chart_of_acc->head_sub_class_name }}</td>
							<td>{{ $chart_of_acc->acc_final_name }}</td>
							<td>{{ ($chart_of_acc->is_active == 1)? trans('others.action_active_label'):trans('others.action_inactive_label') }}</td>
							<td><a href="{{ Route('party_update_view') }}/{{ $chart_of_acc->chart_o_acc_head_id }}"> @include('widgets.button', array('class'=>'success', 'value'=>trans('others.edit_button'))) </a></td>
						</tr>
					@endforeach
	            </tbody>
	        </table>
		</div>
	</div>
@endsection
