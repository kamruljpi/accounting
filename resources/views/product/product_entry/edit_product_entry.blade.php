@extends('layouts.dashboard')
@section('page_heading',trans('others.heading_update_product_label'))
@section('section')

@section('section')
    <div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">{{ trans('others.edit_product_label') }}</div>
					<div class="panel-body">

						<form class="form-horizontal" role="form" method="POST" action="{{ Route('edit_product_action') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="id" value="{{ $product[0]->id }}">
							<input type="hidden" name="group_id" value="{{ $product[0]->group_id }}">

							@if ($errors->any())
							    <div class="alert alert-danger">
							        <ul>
							            @foreach ($errors->all() as $error)
							                <li>{{ $error }}</li>
							            @endforeach
							        </ul>
							    </div>
							@endif

							<div class="form-group">
								<label class="col-md-4 control-label">{{ trans('others.product_group_label') }}</label>
								<div class="col-md-6">
									<select class="form-control input_required" name="p_group" required >
                                        @foreach($productGroups as $productGroup)
                                            <option value="{{ $productGroup->id }}" {{ ($productGroup->id == $product[0]->product_group_id)? "selected":"" }}>{{ $productGroup->name }}</option>
                                        @endforeach
                                    </select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">{{ trans('others.product_name_label') }}</label>
								<div class="col-md-6">
									<input type="text" class="form-control input_required" name="name" autocomplete="off" value="{{ $product[0]->name }}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">{{ trans('others.packet_details_label') }}</label>
								<div class="col-md-6">
									<select style="width:100%" name="packet_ids[]" class="selections" multiple="multiple">
				                        @foreach($packets as $packet)
				                            <option value="{{ $packet->id }}" {{(in_array($packet->id, $productPaketList)? 'selected':'')}}>{{ $packet->name.'('.$packet->quantity.')    '.$packet->unit_name.'('.$packet->unit_quantity.')' }}</option>
				                        @endforeach
				                    </select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">{{ trans('others.product_code_label') }}</label>
								<div class="col-md-6">
									<input type="text" class="form-control input_required" name="code" autocomplete="off" value="@php
										$codes = explode('_', $product[0]->product_code);
										echo $codes[2];
									@endphp">
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-3 col-md-offset-4">
									<div class="select">
										<select class="form-control" type="select" name="isActive" >
											<option  value="1" name="isActive" {{($product[0]->group_id == 1)? 'selected' : '' }}>{{ trans('others.action_active_label') }}</option>
											<option value="0" name="isActive" {{($product[0]->group_id == 0) ? 'selected' : '' }}>{{ trans('others.action_inactive_label') }}</option>
						   				</select>
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<button type="submit" class="btn btn-primary" style="margin-right: 15px;">
										{{ trans('others.update_button') }}
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

    <script type="text/javascript">
        $(".selections").select2();
    </script>
@endsection

