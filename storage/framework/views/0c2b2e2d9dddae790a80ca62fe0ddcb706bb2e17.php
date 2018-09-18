<?php $__env->startSection('page_heading','Chalan List'); ?>
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

           

            <form class="form-horizontal" method="post" id="chalanForm">

                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" required>

                <div class="form-group row">
                    <style type="text/css">
                        input{
                            width: 100% !important;
                        }
                    </style>

                    <div class="col-md-3">
                        <select name="chalan_client_id" class="myselect" required>
                            <option value="">Select Client</option>
                                    <option value=""> </option>
                            
                        </select>
                    </div>

                    <div class="col-md-7 row">
                        <div class="col-md-6 row">
                            <div class="col-md-3">
                                Form:
                            </div>
                            <div class="col-md-9">
                                <input type="date" class="form-control" required placeholder="Search date...." name="chalanDateSearchFldFrom" id="chalanDateSearchFldFrom" value="" >
                                <p id="search"></p>
                            </div>
                        </div>

                        <div class="col-md-6 row">
                            <div class="col-md-3">
                                To:
                            </div>
                            <div class="col-md-9">
                                <input type="date" class="form-control" required placeholder="Search date...." name="chalanDateSearchFldTo" id="chalanDateSearchFldTo" value="">
                                <p id="search"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input class="form-control btn btn-primary btn-outline" type="submit" value="<?php echo e(trans('others.search_btn')); ?>" id="chalanSearchBtn">
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
        
        <div class="chalanDataView" style="display: none;">
            <p class="chalanDetails"></p>
            <p class="dateFrom"></p> 
            <p class="dateTo"></p>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Date</th>
                        <th>Particular</th>
                        <th>Credit/Withdraw</th>
                        <th>Debit/Deposit</th>
                        <th>Balance</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="chalanTableBody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript" src='<?php echo e(asset("js/ledger.js")); ?>'></script>
<script type="text/javascript">
    $(".myselect").select2();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>