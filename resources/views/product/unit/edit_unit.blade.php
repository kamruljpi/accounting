@extends('layouts.dashboard')
@section('page_heading',trans('others.update_unit_label'))
@section('section')

@section('section')
    <div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">{{ trans('others.edit_unit_label') }}</div>
					<div class="panel-body">

						<form class="form-horizontal" role="form" method="POST" action="{{ Route('edit_unit_action') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="id" value="{{ $unit->id }}">

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
								<label class="col-md-4 control-label">{{ trans('others.unit_name_label') }}</label>
								<div class="col-md-6">
									<input type="text" class="form-control input_required" name="name" autocomplete="off" value="{{ $unit->name }}">
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-3 col-md-offset-4">
									<div class="select">
										<select class="form-control" type="select" name="isActive" >
											<option  value="1" name="isActive" {{($unit->is_active == 1)? 'selected' : '' }}>{{ trans('others.action_active_label') }}</option>
											<option value="0" name="isActive" {{($unit->is_active == 0) ? 'selected' : '' }}>{{ trans('others.action_inactive_label') }}</option>
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
@endsection

