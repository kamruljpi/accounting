@extends('layouts.dashboard')
@section('page_heading',trans('others.heading_product_group_list_label'))
@section('section')

@section('section')
	<div class="col-md-10 col-md-offset-1">
		<div>
			<a href="{{ Route('add_product_group_view') }}"> @include('widgets.button', array('class'=>'success', 'value'=>trans('others.add_new_product_group_label')))</a><br><br>
		</div>
		<div>
			<table class="table table-bordered">
	            <thead>
	                <tr>
                        <th class="">{{ trans('others.serial_no_label') }}</th>
                        <th class="">{{ trans('others.product_group_name_label') }}</th>
                        <th class="">{{ trans('others.status_label') }}</th>
                        <th class="">{{ trans('others.action_label') }}</th>
	                </tr>
	            </thead>
	            <tbody>
	            @php ($i = 1) 
		            @foreach($productGroups as $productGroup)
						<tr>
							<td>{{ $i++ }}</td>
							<td>{{ $productGroup->name }}</td>
							<td>{{ ($productGroup->is_active == 1)? trans('others.action_active_label'):trans('others.action_inactive_label')  }}</td>
							<td>
								<a href="{{ Route('edit_productGroup_view') }}/{{ $productGroup->id }}"> @include('widgets.button', array('class'=>'success', 'value'=>trans('others.edit_button'))) </a>
								<a class="delete_id" href="{{ Route('delet_productGroup') }}/{{ $productGroup->id }}/{{ $productGroup->name }}/{{ $productGroup->is_active }}"> @include('widgets.button', array('class'=>'danger', 'value'=>trans('others.delete_button'))) </a>
							</td>
						</tr>
					@endforeach
	            </tbody>
	        </table>
		</div>
	</div>
@endsection
