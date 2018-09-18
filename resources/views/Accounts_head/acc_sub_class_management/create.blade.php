@extends('layouts.dashboard')
@section('page_heading', 'Add new Sub Account Class')
@section('section')

@section('section')
    <div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">Add Sub Account Class</div>
					<div class="panel-body">

						<form class="form-horizontal" role="form" method="POST" action="{{ Route('acc_sub_class_create_action') }}">
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
							{{ old('account_head_name')}}
							<div class="form-group">
								<label class="col-md-4 control-label">Account head name</label>
								<div class="col-md-6">
									<select class="form-control input_required" name="account_head_name" required id="account_head_name">
										<option value="">select</option>
                                        @foreach($accounts_heads as $accounts_head)
                                            <option value="{{ $accounts_head->accounts_heads_id }}"  {{ (old('account_head_name') == $accounts_head->accounts_heads_id)? "selected":"" }}>{{ $accounts_head->head_name_type.'('.$accounts_head->account_code.')' }}</option>
                                        @endforeach
                                    </select>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-4 control-label">Account sub head name</label>
								<div class="col-md-6">
									<select class="form-control input_required" name="account_sub_head_name" id="account_sub_head_name" required {{ (old('account_sub_head_name')!= null)? '':'disabled' }}>
										@if(old('account_sub_head_name')!= null)
											@foreach($accounts_sub_heads as $accounts_sub_head)
												<option value="{{ $accounts_sub_head->accounts_sub_heads_id }}" {{ ($accounts_sub_head->accounts_sub_heads_id == old('account_sub_head_name'))? 'selected':'' }}>{{ $accounts_sub_head->sub_head }}</option>
											@endforeach
										@endif
                                    </select>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-4 control-label">Account head Class name</label>
								<div class="col-md-6">
									<select class="form-control input_required" name="account_head_class_name" id="account_head_class_name" required {{ (old('account_head_class_name')!= null)? '':'disabled' }}>
										@if(old('account_head_class_name')!= null)
											@foreach($acc_class as $acc_clas)
												<option value="{{ $acc_clas->mxp_acc_classes_id }}" {{ ($acc_clas->mxp_acc_classes_id == old('account_head_class_name'))? 'selected':'' }}>{{ $acc_clas->head_class_name }}</option>
											@endforeach
										@endif
                                    </select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">Account Sub class name</label>
								<div class="col-md-6">
									<input type="text" class="form-control input_required" name="acc_sub_class_name" autocomplete="off" value="{{ old('acc_sub_class_name') }}">
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
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="{{ asset("js/get_account_head_details_information.js") }}"></script>
@endsection

