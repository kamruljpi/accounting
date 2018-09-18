@extends('layouts.dashboard')
@section('page_heading','Edit Party')
@section('section')

@section('section')
<a href="{{ Route('party_index') }}"> @include('widgets.button', array('class'=>'success', 'value'=>'Back'))</a><br><br>
    <div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-body">

						<form class="form-horizontal" role="form" method="POST" action="{{ Route('party_update_action') }}" id="acc_update_form">
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
								<label class="col-md-4 control-label">Account head name</label>
								<div class="col-md-6">
									<input type="text" class="form-control input_required" name="accounts_heads_id" autocomplete="off" value="Assets" readonly>
									@foreach($values as $value)
									<input type="hidden" name="accounts_heads_id" value="{{ $value->accounts_heads_id }}">
									@endforeach
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-4 control-label">Account sub head name</label>
								<div class="col-md-6">
									<input type="text" class="form-control input_required" name="accounts_sub_heads_id" autocomplete="off" value="Current Assets" readonly>
									@foreach($values as $value)
									<input type="hidden" name="accounts_sub_heads_id" value="{{ $value->accounts_sub_heads_id }}">
									@endforeach
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">Account head Class name</label>
								<div class="col-md-6">
									<input type="text" class="form-control input_required" name="acc_classes_id" autocomplete="off" value="Party-CA" readonly>
									@foreach($values as $value)
									<input type="hidden" name="acc_classes_id" value="{{ $value->mxp_acc_classes_id }}">
									@endforeach
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">Account Sub head Class name</label>
								<div class="col-md-6">		
									<select class="form-control input_required" name="mxp_acc_head_sub_classes_id" id="account_head_sub_class_id"  required>
									@foreach($values as $value)
										<option value="{{ $value->mxp_acc_head_sub_classes_id }}">
											{{ $value->head_sub_class_name }}
										</option>
									@endforeach	
									@foreach($acc_sub_head_class as $val)
										<option value="{{ $val->mxp_acc_head_sub_classes_id }}">
											{{ $val->head_sub_class_name }}
										</option>
									@endforeach	
                                    </select>
                                  
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">Chart of Account name</label>
								<div class="col-md-6">
									<input type="text" class="form-control input_required" name="chart_of_acc_name" autocomplete="off" value="{{ $value->acc_final_name }}">
									@foreach($values as $value)
									<input type="hidden" name="chart_o_acc_head_id" value="{{ $value->chart_o_acc_head_id }}">
									@endforeach
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3 col-md-offset-4">
									<div class="select">
										<select class="form-control" type="select" name="isActive" >
											@foreach($values as $value)

											<option  <?php if($value->is_active==1){ echo 'selected';}else{echo '';} ?> value="1" name="isActive">Active</option>
											<option <?php if($value->is_active==0){ echo 'selected';}else{echo '';} ?> value="0" name="isActive">Inactive</option>	
	@endforeach
						   				</select>
									</div>
								</div>
							</div>
				            <!-- {{ Session::forget('account_head_sub_class_name') }}
				            {{ Session::forget('account_head_class_name') }}
				            {{ Session::forget('account_head_name') }}
				            {{ Session::forget('account_sub_head_name') }}
				            {{ Session::forget('chart_of_acc_name') }} -->

							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<button type="submit" class="btn btn-primary" style="margin-right: 15px;" id="">Update
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
