<?php $__env->startSection('section'); ?>
<style type="text/css">
    .table{
            margin-bottom: 5px;

    }
    .purchase_table_input input, .purchase_table_input select{
        /*width: 100% !important;*/
    }
    .input_required{
        /*width: 90% !important;*/
    }
    .req_star{
        position: absolute;
        right: 15px;
        top: 25px;
    }
    label{
        width: 100%;
    }
    .col-md-12, .col-md-4, .col-md-3, .col-md-5 {
        padding-left: 0px;
        padding-right: 0px;
    }
    .col-md-2{
        padding-left: 0px;
        padding-right: 0px;
    }

</style>
<div class="col-sm-12" id="demo">
   <div class="row">
        <div class="col-sm-12">
            <?php if(count($errors) > 0): ?>
            <div class="alert alert-danger" role="alert">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <span>
                        <?php echo e($error); ?>

                    </span>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>

            <?php if(Session::has('journal_add')): ?>
                <?php echo $__env->make('widgets.alert', array('class'=>'success', 'message'=> Session::get('journal_add') ), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endif; ?>
            
            


            <?php echo e(Form::open(array('url' => Route('journal_posting_form_action'), 'method' => 'post', 'id'=> 'journal_post_form_id' ))); ?>

                    <div class="all_input_field">
                        <li class="all_input_li_1" >
                            <div id="journal_post_top" class="col-md-12">
                                    <div class="col-md-4">
                                            <label class="company_id_span">Company</label>
                                            <?php echo e(Form::select('company_id[]',$company, null,['class'=>'company_id form-control', 'name'=>'company_id[]'])); ?>


                                            <label class="user_role_span">User (Role)</label>
                                            <?php echo e(Form::select('user_role[]', [], null, ['class'=>'user_role form-control', 'disabled'=> true,'name'=>'user_role[]'])); ?>


                                            <label class="debit_span">Debit</label>
                                            <?php echo e(Form::text('debit[]', '', ['class'=>'debit form-control', 'id'=>'debit_top','name'=>'debit[]'])); ?>


                                    </div>
                                    <div class="col-md-4">
                                            <label class="journal_date_span">Date</label>
                                            <?php echo e(Form::date('journal_date[]',null,['class'=>'journal_date form-control','name'=>'journal_date[]'])); ?>



                                            <label class="char_of_acc_span">Chart Of Acc</label>
                                            <?php echo e(Form::select('char_of_acc[]', $char_of_acc, null, ['class'=>'char_of_acc form-control','name'=>'char_of_acc[]','id'=>'chart_of_acc_top'])); ?>


                                            <label class="credit_span">Credit</label>
                                            <?php echo e(Form::text('credit[]', '', ['class'=>'credit form-control', 'id'=>'credit_top','name'=>'credit[]'])); ?>

                                    </div>
                                    <div class="col-md-4">
                                            <label class="voucher_num_span">Voucher Number</label>
                                            <?php echo e(Form::text('voucher_num[]', '', ['class'=>'voucher_num form-control', 'readonly'=>'true','name'=>'voucher_num[]'])); ?>


                                            <label class="cf_code_span">CF Code</label>
                                            <?php echo e(Form::text('cf_code[]', '', ['class'=>'cf_code form-control','name'=>'cf_code[]'])); ?>



                                            <label class="description_span">Description</label>
                                            <?php echo e(Form::text('description[]', '', ['class'=>'description form-control','name'=>'description[]'])); ?>



                                            <?php echo e(Form::hidden('particulars[]', '', ['class'=>'particulars form-control','name'=>'particulars[]', 'id'=>'particulars'])); ?>


                                    </div>
                            </div>

                            <div id="journal_post_bottom" class="col-md-12">
                                    <div class="col-md-4">
                                            <label class="company_id_bottom_span">Company</label>
                                            <?php echo e(Form::select('company_id[]',$company, null,['class'=>'company_id_bottom form-control','name'=>'company_id_bottom[]'])); ?>


                                            <label class="user_role_bottom_span">User (Role)</label>
                                            <?php echo e(Form::select('user_role[]', [], null, ['class'=>'user_role_bottom form-control', 'disabled'=> true,'name'=>'user_role_bottom[]'])); ?>


                                            <label class="debit_bottom_span">Debit</label>
                                            <?php echo e(Form::text('debit[]', '', ['class'=>'debit_bottom form-control', 'id'=>'debit_top','name'=>'debit_bottom[]'])); ?>


                                          

                                    </div>
                                    <div class="col-md-4">
                                            <label class="journal_date_bottom_span">Date</label>
                                            <?php echo e(Form::date('journal_date[]',null,['class'=>'journal_date_bottom form-control','name'=>'journal_date_bottom[]'])); ?>



                                            <label class="char_of_acc_bottom_span">Chart Of Acc</label>
                                            <?php echo e(Form::select('char_of_acc[]', $char_of_acc, null, ['class'=>'char_of_acc_bottom form-control','name'=>'char_of_acc_bottom[]','id'=>'chart_of_acc_btm'])); ?>


                                            <label class="credit_bottom_span">Credit</label>
                                            <?php echo e(Form::text('credit[]', '', ['class'=>'credit_bottom form-control', 'id'=>'credit_top','name'=>'credit_bottom[]'])); ?>

                                    </div>
                                    <div class="col-md-4">
                                            <label class="voucher_num_bottom_span">Voucher Number</label>
                                            <?php echo e(Form::text('voucher_num[]', '', ['class'=>'voucher_num_bottom form-control', 'readonly'=>'true','name'=>'voucher_num_bottom[]'])); ?>


                                            <label class="cf_code_bottom_span">CF Code</label>
                                            <?php echo e(Form::text('cf_code[]', '', ['class'=>'cf_code_bottom form-control','name'=>'cf_code_bottom[]'])); ?>


                                            <label class="description_bottom_span">Description</label>
                                            <?php echo e(Form::text('description[]', '', ['class'=>'description_bottom form-control','name'=>'description_bottom[]'])); ?>



                                            <?php echo e(Form::hidden('particulars[]', '', ['class'=>'particulars_bottom form-control','readonly'=>'true','name'=>'particulars_bottom[]','id'=>'particulars_bottom','hidden'=>'true'])); ?>



                                    </div>
                            </div>
                        </li>
                    </div>

                    <input name="_token" type="hidden" value="<?php echo e(csrf_token()); ?>">
                    <input name="company_id[]" type="hidden" value="<?php echo e(session()->get('company_id')); ?>">
                    <input name="com_group_id[]" type="hidden" value="<?php echo e(session()->get('group_id')); ?>">

                    <div class="col-md-12">
                        <div class="col-md-2">
                            <button class="form-control" id="journal_post_add_row">Add Row</button>
                        </div>
                        <div class="col-md-2">
                            <button class="form-control" id="journal_delete_row">Delete Row</button>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-4">
                            <?php echo e(Form::submit('Submit', ['class'=>'form-control', 'id' => 'journal_post_form_submit_id'])); ?>

                        </div>
                    </div>

            <?php echo e(Form::close()); ?>






        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>