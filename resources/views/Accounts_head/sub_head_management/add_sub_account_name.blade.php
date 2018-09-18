@extends('layouts.dashboard')
@section('page_heading', 'Add new sub Accounts name/type')
@section('section')

@section('section')
    <div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">Add sub Accounts name/type</div>
					<div class="panel-body">

						<form class="form-horizontal" role="form" method="POST" action="{{ Route('add_sub_account_head_action') }}">
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
								<label class="col-md-4 control-label">Account name/type</label>
								<div class="col-md-6">
									<select class="form-control input_required" name="account_name" required >
										<option value="">select</option>
                                        @foreach($accounts_heads as $accounts_head)
                                            <option value="{{ $accounts_head->accounts_heads_id }}" {{ (old('account_name') == $accounts_head->accounts_heads_id)? "selected":"" }}>{{ $accounts_head->head_name_type.'('.$accounts_head->account_code.')' }}</option>
                                        @endforeach
                                    </select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">Sub Account name/type</label>
								<div class="col-md-6">
									<input type="text" class="form-control input_required" name="sub_account_name" autocomplete="off" value="{{ old('sub_account_name') }}">
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

