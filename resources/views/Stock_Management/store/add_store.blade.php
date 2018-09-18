@extends('layouts.dashboard')
@section('page_heading',trans('others.heading_add_new_stock_label'))
@section('section')

@section('section')
    <div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">{{ trans('others.add_stock_label') }}</div>
					<div class="panel-body">

						<form class="form-horizontal" role="form" method="POST" action="{{ Route('add_store_action') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">

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
								<label class="col-md-4 control-label">{{ trans('others.enter_store_name_label') }}</label>
								<div class="col-md-6">
									<input type="text" class="form-control input_required" name="name" autocomplete="off">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">{{ trans('others.enter_store_location_label') }}</label>
								<div class="col-md-6">
									<input type="text" class="form-control input_required" name="location" autocomplete="off">
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-3 col-md-offset-4">
									<div class="select">
										<select class="form-control" type="select" name="isActive" >
											<option  value="1" name="isActive" >{{ trans('others.action_active_label') }}</option>
											<option value="0" name="isActive" >{{ trans('others.action_inactive_label') }}</option>
									   </select>
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<button type="submit" class="btn btn-primary" style="margin-right: 15px;">
										{{ trans('others.save_button') }}
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

