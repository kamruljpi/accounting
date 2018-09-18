@extends('layouts.dashboard')
@section('page_heading',trans('others.mxp_menu_update_stocks_action'))
@section('section')

@section('section')
		<div>
			<div>
				<div class="">
					<a href="{{ Route('stock_view') }}"> @include('widgets.button', array('class'=>'info', 'value'=>trans('others.heading_add_stock_label'))) </a>
				</div>
			</div>
			<br>

			@include('layouts.pagination',['route_name'=>'update_stocks_view'])

			<table class="table table-bordered">
	            <thead>
	                <tr>
                        <th>{{trans('others.serial_no_label')}}</th>
						<th>{{trans('others.product_name_label')}}</th>
						<th>{{trans('others.product_group_label')}}</th>
						<th>{{trans('others.quantity_label')}}</th>
                        <th>{{trans('others.company_name_label')}}</th>
						<th>{{trans('others.mxp_menu_store')}}</th>
						<th>{{ trans('others.action_label') }}</th>
	                </tr>
	            </thead>
	            <tbody>
	            	@php ($i = 1) 
		            @foreach($stocks as $stock)
						<tr>
							<td>{{ $i++ }}</td>
							<td>{{ $stock->product_name.' ('.$stock->packet_name.'('.$stock->packet_quantity.')	'.$stock->unit_name.'('.$stock->unit_quantity.'))' }}</td>
							<td>{{ $stock->product_group }}</td>
							<td>{{ $stock->quantity }}</td>
							<td>{{ $stock->client_name }}</td>

							<form role="form" action="{{ Route('update_stock_action') }}" method="post">

                				<input type="hidden" name="_token" value="{{ csrf_token() }}">
                				<input type="hidden" name="id" value="{{ $stock->id }}">

			                    <td>
									<select class="form-control input_required" name="location" required >
	                                    <option value="">{{ trans('others.option_select_location_label') }}</option>
	                                    
	                                    @foreach($stock_states as $stock_state)
	                                        <option value="{{ $stock_state->id }}" {{ ($stock->store_id == $stock_state->id)? "selected":"" }}>{{ $stock_state->name }}</option>
	                                    @endforeach
	                                </select>
	                            </td>
	                            <td>
	                            	<input style="float: left; width: 100% !important" class="form-control btn btn-info" type="submit" value="{{ trans('others.update_button') }}" >
	                            </td>
			            	</form>
						</tr>
					@endforeach
	            </tbody>
	        </table>
		</div>
	</div>
@endsection
