<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"  aria-hidden="true" >&times;</button>
                <h3 class="modal-title">Add new Client</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form class="form-horizontal" method="post" id="create_urgent_client_post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <!--
                        @if(Session::has('validation_error_in_add_client'))
                            @include('widgets.alert', array('class'=>'danger', 'message'=> Session::get('validation_error_in_add_client') ))
                        @endif -->

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <?php echo (Session::forget('validation_error_in_add_client')); ?>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Client Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control  input_required" name="name" value="{{ old('clent_name')  }}" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Client Email</label>
                            <div class="col-md-6">
                                <input type="email" class="form-control  input_required" name="email" value="{{ old('client_mail')  }}" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Client Phone Number</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control  input_required" name="phone" value="{{ old('client_mobile_num')  }}" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Client Address</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control  input_required" name="address" value="{{ old('client_address')  }}" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Select Company</label>
                            <div class="col-md-6">
                                <select class="form-control input_required" name="company_id" >
                                    <option value="" disabled="">Select Company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3 col-md-offset-4">
                                <div class="select">
                                    <select class="form-control" type="select" name="is_active" >
                                        <option  value="1" name="is_active" >Active</option>
                                        <option value="0" name="is_active" >InActive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="group_id" value="{{ session()->get('group_id') }}">


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <input type="submit" name="save" value="Save" class="btn btn-primary" id="mybtn">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                {{-- <!-- <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('product.print_btn') }}</button> --> --}}
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="{{ asset("js/custom.js") }}"></script>
