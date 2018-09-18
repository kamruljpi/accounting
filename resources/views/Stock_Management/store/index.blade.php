@extends('layouts.dashboard')
@section('page_heading',trans('others.heading_store_list_label'))
@section('section')

@section('section')
	<div class="col-md-10 col-md-offset-1">
		<div>
			<a href="{{ Route('add_store_view') }}"> @include('widgets.button', array('class'=>'success', 'value'=>trans('others.heading_add_new_stock_label')))</a><br><br>
		</div>
		<div class="text-center">
			{{ $stores->links() }}
		</div>
		<div>
			<table class="table table-bordered">
	            <thead>
	                <tr>
						<th>{{trans('others.serial_no_label')}}</th>
						<th>{{trans('others.store_name_label')}}</th>
						<th>{{trans('others.store_location_label')}}</th>
						<th>{{trans('others.status_label')}}</th>
						<th>{{trans('others.action_label')}} </th>
	                </tr>
	            </thead>
	            <tbody>
	            @php ($i = 1) 
		            @foreach($stores as $store)
						<tr>
							<td>{{ $i++ }}</td>
							<td>{{ $store->name }}</td>
							<td>{{ $store->location }}</td>
							<td>{{ ($store->status == 1)? trans('others.action_active_label'):trans('others.action_inactive_label') }}</td>
							<td> 
								<a href="{{ Route('edit_store_action') }}/{{ $store->id }}"> @include('widgets.button', array('class'=>'success', 'value'=>trans('others.update_button'))) </a>
								<a class="delete_id" href="{{ Route('delete_store_action') }}/{{ $store->id }}"> @include('widgets.button', array('class'=>'danger', 'value'=>trans('others.delete_button'))) </a>
							</td>
						</tr>
					@endforeach
	            </tbody>
	        </table>
		</div>
	</div>
@endsection
