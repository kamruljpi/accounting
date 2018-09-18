@extends('layouts.dashboard')
@section('page_heading',trans('others.mxp_menu_product'))
@section('section')

@section('section')
	<div class="col-md-10 col-md-offset-1">
		<div>
			<a href="{{ Route('add_product_entry_view') }}"> @include('widgets.button', array('class'=>'success', 'value'=>trans('others.heading_add_new_packet_label')))</a><br><br>
		</div>

		@include('layouts.pagination',['route_name'=>'entry_product_list_view'])

		<div>
			<table class="table table-bordered">
	            <thead>
	                <tr>
                        <th class="">{{ trans('others.serial_no_label') }}</th>
                        <th class="">{{ trans('others.packet_details_label') }}</th>
                        <th class="">{{ trans('others.product_group_label') }}</th>
                        <th class="">{{ trans('others.status_label') }}</th>
                        <th class="">{{ trans('others.action_label') }}</th>
	                </tr>
	            </thead>
	            <tbody>
	            	@php ($i = 1)
		            @foreach($products as $product)
						<tr>
							<td>{{ $i++ }}</td>
							<td>{{ $product->packet_name }}</td>
							<td>{{ $product->pord_grp_name }}</td>
							<td>{{ ($product->is_active == 1)? trans('others.action_active_label'):trans('others.action_inactive_label') }}</td>
							<td>
								<a href="{{ Route('edit_product_entry_view') }}/{{ $product->group_id }}"> @include('widgets.button', array('class'=>'success', 'value'=>trans('others.edit_button')))</a>
								<a class="delete_id" href="{{ Route('delete_product_entry_action') }}/{{ $product->id }}"> @include('widgets.button', array('class'=>'danger', 'value'=>trans('others.delete_button'))) </a>
							</td>
						</tr>
					@endforeach
	            </tbody>
	        </table>
		</div>
	</div>
@endsection

