@extends('layouts.dashboard')
@section('page_heading',trans('others.heading_acc_clas_list_label'))
@section('section')

@section('section')
	<div class="col-md-10 col-md-offset-1">
		<div>
			<a href="{{ Route('acc_class_create_view') }}"> @include('widgets.button', array('class'=>'success', 'value'=>'Add Account class'))</a><br><br>
		</div>

		@include('layouts.pagination',['route_name'=>'acc_class_index'])

		<div>
			<table class="table table-bordered">
	            <thead>
	                <tr>
						<th>{{trans('others.serial_no_label')}}</th>
						<th>Account head/type</th>
						<th>Account sub head/type</th>
						<th>Account head class name</th>
						<th>{{trans('others.status_label')}}</th>
						<th>{{trans('others.action_label')}} </th>
	                </tr>
	            </thead>
	            <tbody>
	            @php ($i = 1) 
		            @foreach($acc_class as $acc_clas)
						<tr>
							<td>{{ $i++ }}</td>
							<td>{{ $acc_clas->account_head_details }}</td>
							<td>{{ $acc_clas->sub_head }}</td>
							<td>{{ $acc_clas->head_class_name }}</td>
							<td>{{ ($acc_clas->is_active == 1)? trans('others.action_active_label'):trans('others.action_inactive_label') }}</td>
							<td> 
								<a href="{{ Route('acc_class_update_view') }}/{{ $acc_clas->mxp_acc_classes_id }}"> @include('widgets.button', array('class'=>'success', 'value'=>trans('others.edit_button'))) </a>
								<a class="delete_id" href="{{ Route('acc_class_delete_action') }}/{{ $acc_clas->mxp_acc_classes_id }}"> @include('widgets.button', array('class'=>'danger', 'value'=>trans('others.delete_button'))) </a>
							</td>
						</tr>
					@endforeach
	            </tbody>
	        </table>
		</div>
	</div>
@endsection


