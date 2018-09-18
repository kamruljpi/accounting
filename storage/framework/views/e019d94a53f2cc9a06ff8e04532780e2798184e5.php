<?php $__env->startSection('section'); ?>
<style type="text/css">
    .table{
            margin-bottom: 5px;

    }
    .purchase_table_input input, .purchase_table_input select{
        width: 90% !important;]
    }
    .input_required{
        /*width: 90% !important;*/
    }
    .req_star{
        position: absolute;
        right: 10px;
        top: 25px;
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

            <?php if(Session::has('product_purchase')): ?>
                <?php echo $__env->make('widgets.alert', array('class'=>'success', 'message'=> Session::get('product_purchase') ), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endif; ?>
            <?php if(Session::has('product_purchase_warrning')): ?>
                <?php echo $__env->make('widgets.alert', array('class'=>'danger', 'message'=> Session::get('product_purchase_warrning') ), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endif; ?>

            <div class="purchase_table_input">
                <form action="<?php echo e(Route('get_from_value')); ?>" method="post" role="form" id="prch_form" >
                    <div class="purchase_table_left col-sm-5">
                        <div class="col-md-12">
                                <div class="col-md-6" id="select_client">
                                    <label><?php echo e(trans('others.client_label')); ?>: </label>
                                    <div id="divForSelectingClient"></div>
                                    <select class="form-control input_required" name="client" id="client" class="client">
                                        <option value=""><?php echo e(trans('others.select_client_option')); ?></option>
                                        
                                         <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($client->chart_o_acc_head_id); ?>"> <?php echo e($client->acc_final_name); ?> </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                        <div class="UrgentClientView"></div>
                                </div>
             
                              
                                <div class="col-md-6">
                                    <label><?php echo e(trans('others.mxp_date')); ?>: </label>
                                    <input class="form-control input_required" type="date" name="date" id="date">
                                </div>
                        </div>

                        <div class="col-md-12">
                            <label><?php echo e(trans('others.mxp_menu_product_group')); ?>: </label>
                            <select class="form-control input_required" name="product_group" id="product_group">
                                <option value=""><?php echo e(trans('others.mxp_select_product_group')); ?></option>
                                <?php $__currentLoopData = $productGroup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($group->id); ?>"> <?php echo e($group->name); ?> </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label><?php echo e(trans('others.mxp_menu_product')); ?> : </label>
                            <input type="hidden" name="product_code" id="product_code">
                            <select class="form-control input_required" name="product" id="product" disabled>

                            </select>
                        </div>
                        <div class="col-md-12">
                            <label><?php echo e(trans('others.heading_chart_of_acc_list_label')); ?>: </label>

                            <select class="form-control input_required" name="chart_of_acc" id="chart_of_acc" required>
                            <option value=""><?php echo e(trans('others.select_account_option')); ?></option>
                            <?php $__currentLoopData = $chart_of_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chart_of_account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($chart_of_account->chart_o_acc_head_id); ?>"> <?php echo e($chart_of_account->acc_final_name); ?> </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="purchase_table_left col-sm-7">
                        <div class="col-md-12">
                            <label>Invoice NO: </label>
                            <input class="form-control input_required" type="text" name="invoice" id="invoice">
                        </div>

                        <div class="col-md-12 vat_tax_row different_input_field ">
                                    <div class="col-md-6">
                                        <label><?php echo e(trans('others.vat_label')); ?></label>
                                        <input class="form-control" name="vat_percentage" placeholder="VAT (%)" type="text">
                                    </div>
                                     
                                    <label><?php echo e(trans('others.tax_label')); ?></label>
                                    <div class="col-md-6">
                                        <input class="form-control" name="tax_percentage" placeholder="TAX (%)" type="text">
                                    </div>
                        </div>

                        <div class="col-md-12   purchase_table_right_bottom">
                           <div class="col-md-3">
                                <label><?php echo e(trans('others.quantity_label')); ?></label>
                                <input class="form-control input_required" type="text" name="quantity">
                            </div>
                            <div class="col-md-3">
                                <label><?php echo e(trans('others.unit_price_per_kg_label')); ?></label>
                                <input class="form-control input_required" type="text" name="price">
                            </div>
                            <div class="col-md-3">
                                <label><?php echo e(trans('others.total_price_label')); ?></label>
                                <input class="form-control input_required" type="text" name="total_price" id="total_price" readonly>
                            </div>
                            <div class="col-md-3">
                                <label><?php echo e(trans('others.bonus_label')); ?></label>
                                <input class="form-control" type="text" name="bonus">
                            </div>
                        </div>

                        <div class="col-md-12 row paid_due_right_bottom">
                           <div class="col-md-4">
                                <label><?php echo e(trans('others.paid_amount_label')); ?></label>
                                <input class="form-control input_required" type="text" name="paid_amount">
                            </div>
                            <div class="col-md-1">
                                <input class="form-control" type="hidden" name="due_amount" id="due_amount" readonly>
                            </div>
                            <div class="col-md-3">
                                <label><?php echo e(trans('others.amount_to_pay_label')); ?></label>
                                <input class="form-control" type="text" name="total_price_with_vat_tax" id="total_price_with_vat_tax" readonly>
                            </div>
                            <div class="col-md-4">
                                <br>
                                <input name="_token" type="hidden" value="<?php echo e(csrf_token()); ?>">
                                <button class="btn btn-success purchase_data_add col-md-12"><?php echo e(trans('others.add_row_button')); ?></button>
                            </div>
                        </div>
                    </div>
                </form>    
            </div>

            <div class="purchase_table col-md-12">
                <div class="view_form"></div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>