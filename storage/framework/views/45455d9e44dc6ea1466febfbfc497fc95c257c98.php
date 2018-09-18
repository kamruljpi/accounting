<?php $__env->startSection('page_heading','Purchased Products List'); ?>
<?php $__env->startSection('section'); ?>

<style type="text/css">
    .table{
            margin-bottom: 5px;
    }
</style>

<div class="col-sm-12" id="demo">
    <div class="row">
        <div class="col-sm-12">
            <?php if(count($errors) > 0): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <li><span><?php echo e($error); ?></span></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
            <?php endif; ?>

            <?php if(Session::has('purchase_table')): ?>
                <?php echo $__env->make('widgets.alert', array('class'=>'success', 'message'=> Session::get('purchase_table') ), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endif; ?>

            <form role="form" action="<?php echo e(Route('product_purchase_search_list')); ?>" method="post">

                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" required>

                <div class="form-group row">

                    
                    <style type="text/css">
                        input{
                            width: 100% !important;
                        }
                    </style>

                    <div class="col-md-2">
                        <select name="client_id" class="myselect">
                            <option value="">Select Client</option>
                            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($client->chart_o_acc_head_id); ?>" <?php echo e(((Session::get('client_id_for_select_field')) == $client->chart_o_acc_head_id)? "selected":""); ?>><?php echo e($client->acc_final_name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="invoice_id" class="myselect">
                            <option value="">Select Invoice</option>
                            <?php $__currentLoopData = $invoice_codes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice_code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($invoice_code->id); ?>" <?php echo e(((Session::get('invoice_id_for_select_field')) == $invoice_code->id)? "selected":""); ?>><?php echo e($invoice_code->invoice_code); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="stock_status" class="form-control">
                            <option value="" name="stock_status">Select stock</option>
                            <option value=1 <?php echo e(((Session::get('stock_status')) == '1')? "selected":""); ?> name="stock_status">Stocked</option>
                            <option value=0 <?php echo e(((Session::get('stock_status')) == '0')? "selected":""); ?> name="stock_status">Not yet stocked</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <input type="date" class="form-control" placeholder="Search date...." name="dateSearchFldFrom" id="dateSearchFldFrom" value="<?php echo e(Session::get('fromDate')); ?>" >
                        <p id="search"></p>
                    </span>
                    </div>

                    <div class="col-md-2">
                        <input type="date" class="form-control" placeholder="Search date...." name="dateSearchFldTo" id="dateSearchFldTo" value="<?php echo e(Session::get('toDate')); ?>">
                        <p id="search"></p>
                    </span>
                    </div>

                    <?php echo e(Session::forget('client_id_for_select_field')); ?>

                    <?php echo e(Session::forget('invoice_id_for_select_field')); ?>

                    <?php echo e(Session::forget('fromDate')); ?>

                    <?php echo e(Session::forget('toDate')); ?>

                    <?php echo e(Session::forget('stock_status')); ?>


                    <div class="col-md-2">
                        <input class="form-control btn btn-primary btn-outline" type="submit" value="<?php echo e(trans('others.search_btn')); ?>" >
                    </span>
                    </div>
                </div>
            </form>

            <?php if($currentPage > 0): ?>
                <?php echo $__env->make('layouts.pagination',['route_name'=>'product_purchase_list_view'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endif; ?>

            <form role="form" action="<?php echo e(Route('product_purchase_add')); ?>" method="post">

                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">

                <table class="table table-bordered purchase_table" id="tblSearch">
                    <thead>
                        <tr>
                            <th class="">Serial</th>
                            <th class="">Product Name</th>
                            <th class="">Client</th>
                            <th class="">Quantity</th>
                            <th class="">Price/unit</th>

                            <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th class=""><?php echo e($column->name); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <th class="">Total Amount</th>
                            <th class="">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php ($i = 1); ?>
                        <?php $__currentLoopData = $purchase_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase_product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($i++); ?></td>
                                <td><?php echo e($purchase_product->product_name.'( '.$purchase_product->packet_name.'('.$purchase_product->packet_quantity.')  '.$purchase_product->unit_name.'('.$purchase_product->unit_quantity.') )'); ?></td>
                                <td><?php echo e($purchase_product->client_name); ?></td>
                                <td><?php echo e($purchase_product->quantity); ?></td>
                                <td><?php echo e($purchase_product->price); ?></td>

                                <?php 
                                    $all_vat_taxs = explode(',', $purchase_product->new_vat_tax);
                                    $vat_tax_ids = explode(',', $purchase_product->vat_tax_id);
                                    $vat_tax_names = explode(',', $purchase_product->vat_tax_name);
                                    foreach ($columns as $column) {
                                        if(in_array($column->id, $vat_tax_ids)){
                                            echo "<td>".$all_vat_taxs[array_search($column->id, $vat_tax_ids)]."</td>";
                                        }
                                        else{
                                            echo "<td>0</td>";
                                        }
                                    }
                                 ?>

                                <td><?php echo e($purchase_product->total_amount_with_vat); ?></td>
                                <td>
                                    <a name="purchase_product_details_modal_view" data-index="<?php echo e($purchase_product->id); ?>"> <?php echo $__env->make('widgets.button', array('class'=>'info', 'value'=>trans('others.view_btn')), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> </a>
                                    <a name="purchase_product_update_modal_view" data-index="<?php echo e($purchase_product->id); ?>"> <?php echo $__env->make('widgets.button', array('class'=>'primary', 'value'=>trans('others.edit_btn')), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> </a>
                                    <div class="productViewModal"></div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".myselect").select2();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>