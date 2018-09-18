@extends('layouts.dashboard')
@section('page_heading',trans('others.heading_add_stock_label'))
@section('section')

@section('section')
	<div>
		<div {{-- style="display: none;" --}} >
			<div class="">
				<a href="{{ Route('update_stocks_view') }}"> @include('widgets.button', array('class'=>'info', 'value'=> trans('others.mxp_menu_update_stocks_action') )) </a>
			</div>
		</div>
		<br>

		{{--@include('layouts.pagination',['route_name'=>'stock_view'])--}}

		<h4>Local Purchase Products</h4>
		<div>
			<table class="table table-bordered">
	            <thead>
	                <tr>
                        <th>{{trans('others.serial_no_label')}}</th>
						<th>{{trans('others.product_name_label')}}</th>
						<th>{{trans('others.product_group_label')}}</th>
						<th>{{trans('others.quantity_label')}}</th>
						<th>{{trans('others.unit_label')}}</th>
                        <th>{{trans('others.company_name_label')}}</th>
						<th>{{trans('others.mxp_menu_store')}}</th>
						<th>{{ trans('others.action_label') }}</th>
	                </tr>
	            </thead>
	            <tbody>
	            	@php ($i = 1) 
		            @foreach($products as $product)
						<tr>
							<td>{{ $i++ }}</td>
							<td>{{ $product->product_name.' ('.$product->packet_name.'('.$product->packet_quantity.')	'.$product->unit_name.'('.$product->unit_quantity.'))' }}</td>
							<td>{{ $product->product_group }}</td>
							<td>{{ $product->quantity }}</td>
							<td>{{ $product->unit_name }}</td>
							<td>{{ $product->client_name }}</td>

							<form role="form" action="{{ Route('save_stock_action') }}" method="post">

                				<input type="hidden" name="_token" value="{{ csrf_token() }}">
                				<input type="hidden" name="local_pro_id" value="{{ $product->id }}">

			                    <td>
									<select class="form-control input_required" name="location" required >
	                                    <option value="">{{ trans('others.option_select_location_label') }}</option>
	                                    @foreach($stock_states as $stock_state)
	                                        <option value="{{ $stock_state->id }}">{{ $stock_state->name }}</option>
	                                    @endforeach
	                                </select>
	                            </td>
	                            <td>
	                            	<input style="float: left; width: 100% !important" class="form-control btn btn-warning" type="submit" value="{{ trans('others.save_button') }}" >
	                            </td>
			            	</form>
						</tr>
					@endforeach
	            </tbody>
	        </table>
		</div>

		<h4>L/C Purchase Products</h4>
		<div>
			<table class="table table-bordered">
				<thead>
				<tr>
					<th>{{trans('others.serial_no_label')}}</th>
					<th>{{trans('others.product_name_label')}}</th>
					<th>{{trans('others.product_group_label')}}</th>
					<th>{{trans('others.quantity_label')}}</th>
					<th>{{trans('others.unit_label')}}</th>
					<th>{{trans('others.company_name_label')}}</th>
					<th>{{trans('others.mxp_menu_store')}}</th>
					<th>{{ trans('others.action_label') }}</th>
				</tr>
				</thead>
				<tbody>
				@php ($i = 1)
				@foreach($lcproducts as $product)
					<tr>
						<td>{{ $i++ }}</td>
						<td>{{ $product->product_details }}</td>
						<td>{{ $product->product_group }}</td>
						<td>{{ $product->quantity }}</td>
						<td>{{ $product->unit }}</td>
						<td>{{ $product->client_details }}</td>

						<form role="form" action="{{ Route('save_stock_action') }}" method="post">

							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="lc_purchase_id" value="{{ $product->lc_purchase_id }}">

							<td>
								<select class="form-control input_required" name="location" required >
									<option value="">{{ trans('others.option_select_location_label') }}</option>
									@foreach($stock_states as $stock_state)
										<option value="{{ $stock_state->id }}">{{ $stock_state->name }}</option>
									@endforeach
								</select>
							</td>
							<td>
								<input style="float: left; width: 100% !important" class="form-control btn btn-warning" type="submit" value="{{ trans('others.save_button') }}" >
							</td>
						</form>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection
