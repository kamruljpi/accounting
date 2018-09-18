
<?php $__env->startSection('page_heading','Sale Return List'); ?>
<?php $__env->startSection('section'); ?>
<style type="text/css">
    .table{
            margin-bottom: 5px;
    }
</style>

<div class="col-sm-12" id="demo">
    <div class="row">
        <div class="col-sm-12 input_table_specific">
            <?php if(count($errors) > 0): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <li><span><?php echo e($error); ?></span></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
            <?php endif; ?>


            <form class="form-horizontal" action="<?php echo e(Route('sales_return_get')); ?>" method="post" id="chalanForm">

                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" required>

                <div class="form-group row">
                    <style type="text/css">
                        input{
                            width: 100% !important;
                        }
                    </style>

                    <div class="col-md-3">
                        <select name="order_no" class="myselect" required>
                            <option value="">Select Order No</option>
                            <?php $__currentLoopData = $get_oreder_no; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($order->order_no); ?>"><?php echo e($order->order_no); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                        </select>   
                    </div>

                    <div class="col-md-7 row">
                    </div>
                    <div class="col-md-2">
                        <input class="form-control btn btn-primary" type="submit" value="<?php echo e(trans('others.search_btn')); ?>" id="salereturnSearchBtn" >
                    </div>
                </div>
            </form>
        </div>

        <div class="preLoadImageView" style="display: none;">
            <center>
                <img src="<?php echo e(asset("assets/images/preLoader_2.gif")); ?>">
            </center>
        </div>
        <div class="chalanDetailsView"></div>
        <div class="dataNotFoundMessage" style="margin-top: 10%; margin-left: 9%;"></div>
        
        <div class="saleDataView" style="display: block;">
           <form method="post" action="<?php echo e(Route('sales_return_store')); ?>" id="frm_value" enctype="multipart/form-data">
             
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Sale Date</th>
                        <th>Particular Name</th>
                        <th>Transport Date</th>
                        <th>Invoice No</th>
                        <th>Client</th>
                        <th>Quantity</th>
                        <th>Price/Unit</th>
                        <th>Due</th>
                        <th>Bonus</th>
                        <th>Total Price</th>
                        <th>Chart of Account</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                     <?php if($slreturn): ?>
            <?php $__currentLoopData = $slreturn; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" required>
                    <tr>
                        <td><?php echo e($val->order_no); ?></td>
                        <td><?php echo e($val->sale_date); ?></td>
                        <td>
                            <?php echo e($val->p_name); ?><?php echo e($val->pkt_name); ?>(<?php echo e($val->unit_quantity); ?>)<?php echo e($val->u_name); ?>(<?php echo e($val->unit_quantity); ?>)
                       <input type="hidden" id="" name="product_id[]"
                            value="<?php echo e($val->product_id); ?>" />
                            <input type="hidden" id="" name="product_name[]"
                            value="<?php echo e($val->p_name); ?><?php echo e($val->pkt_name); ?>(<?php echo e($val->unit_quantity); ?>)<?php echo e($val->u_name); ?>(<?php echo e($val->unit_quantity); ?>)" />
                        </td>
                        <td><?php echo e($val->transport_date); ?></td>
                        <td><?php echo e($val->invoice_code); ?></td>
                        <td><?php echo e($val->acc_final_name); ?>

                        <input type="hidden" name="jurnalParticular[]"
                            value="<?php echo e($val->acc_final_name); ?>" /></td>
                        <td><?php echo e($val->slquantity); ?>

                         <input type="hidden" name="slquantity[]"
                            value="<?php echo e($val->slquantity); ?>" /></td>
                        <td><?php echo e($val->price_per_unit); ?>

                        <input type="hidden" name="price_per_unit[]"
                            value="<?php echo e($val->price_per_unit); ?>" />
                        </td>
                        <td><?php echo e($val->due_ammount); ?>

                        <input type="hidden" name="due_ammount[]"
                            value="<?php echo e($val->due_ammount); ?>" /></td>
                        <td><?php echo e($val->bonus); ?>

                            <input type="hidden" name="bonus[]"
                            value="<?php echo e($val->bonus); ?>" /></td>
                        <td><?php echo e($val->total_amount); ?>

                            <input type="hidden" name="total_ammount[]"
                            value="<?php echo e($val->total_amount); ?>" />
                        </td>
                        <td>
                            <select class="chart_acc" name="chart_of_acc[]" id="chart_of_acc">
                                <option value="">-- Select --</option>
                                <?php $__currentLoopData = $chartOfAccs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chartOfAcc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($chartOfAcc->chart_o_acc_head_id); ?>"> <?php echo e($chartOfAcc->acc_final_name); ?> </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </td>
                        <input type="hidden" name="client_id[]" value="<?php echo e($val->client_id); ?>">
                        <td><input type="checkbox" id="returs_sale_add" name="lot_id[]"
                            value="<?php echo e($val->lot_id); ?>" />
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
          <?php endif; ?>
                </tbody>
            </table>
           <div class="col-md-2">
                <a href=""><input class="form-control btn btn-primary" type="submit" value="submit" id="returnsave"></a>
            </div>
            </form>
        </div>

    </div>   
</div>
<script type="text/javascript">
    $(".myselect").select2();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>