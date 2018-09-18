<?php $__env->startSection('page_heading','Challan'); ?>
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

      

            <form class="form-horizontal" method="post" id="GenerateForm">

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
                        <select name="order_id" class="myselect">
                            <option value="">Order No</option>
                            <?php $__currentLoopData = $orderNo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $OrderNo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($OrderNo->order_no); ?>" ><?php echo e($OrderNo->order_no); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                   
                    <div class="col-md-2">
                        <input class="form-control btn btn-primary btn-outline" type="submit" value="<?php echo e(trans('others.genarate')); ?>" id="chalanGenagareBtn">
                    </div>
                </div>
            </form>
        </div>
<div class="preLoad" style="display: none;">
            <center>
                <img src="<?php echo e(asset("assets/images/preLoader_2.gif")); ?>">
            </center>
        </div>
        <div class="kol" style="display:none;">
          <!--  <?php echo $__env->make('chalan.generated-chalan', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> -->
        </div>
        

        
    </div>
</div>

<script type="text/javascript" src='<?php echo e(asset("js/ledger.js")); ?>'></script>
<script type="text/javascript">
    $(".myselect").select2();
</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>