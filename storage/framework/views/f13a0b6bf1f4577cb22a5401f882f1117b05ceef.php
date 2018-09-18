
<?php $__env->startSection('page_heading','Party Ledger List'); ?>
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

           

            <form class="form-horizontal" method="post" id="partyLedgerForm">

                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" required>

                <div class="form-group row">
                    <style type="text/css">
                        input{
                            width: 100% !important;
                        }
                    </style>

                    <div class="col-md-3">
                        <select name="ledger_client_id" class="myselect" required>
                            <option value="">Select Client</option>
                             <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($client->chart_o_acc_head_id); ?>"<?php echo e(((Session::get('ledger_client_id_for_select_field')) == $client->chart_o_acc_head_id)? "selected":""); ?>

                                     > <?php echo e($client->acc_final_name); ?> </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                        </select>
                    </div>

                    <div class="col-md-7 row">
                        <div class="col-md-6 row">
                            <div class="col-md-3">
                                Form:
                            </div>
                            <div class="col-md-9">
                                <input type="date" class="form-control" required placeholder="Search date...." name="ledgerDateSearchFldFrom" id="ledgerDateSearchFldFrom" value="<?php echo e(Session::get('ledger_fromDate')); ?>" >
                                <p id="search"></p>
                            </div>
                        </div>

                        <div class="col-md-6 row">
                            <div class="col-md-3">
                                To:
                            </div>
                            <div class="col-md-9">
                                <input type="date" class="form-control" required placeholder="Search date...." name="ledgerDateSearchFldTo" id="ledgerDateSearchFldTo" value="<?php echo e(Session::get('ledger_toDate')); ?>">
                                <p id="search"></p>
                            </div>
                        </div>
                    </div>

                    <?php echo e(Session::forget('ledger_client_id_for_select_field')); ?>

                    <?php echo e(Session::forget('ledger_fromDate')); ?>

                    <?php echo e(Session::forget('ledger_toDate')); ?>


                    <div class="col-md-2">
                        <input class="form-control btn btn-primary btn-outline" type="submit" value="<?php echo e(trans('others.search_btn')); ?>" id="partyLedgerSearchBtn">
                    </div>
                </div>
            </form>
        </div>

        <div class="preLoadImageView" style="display: none;">
            <center>
                <img src="<?php echo e(asset("assets/images/preLoader_2.gif")); ?>">
            </center>
        </div>
        <div class="ledgerDetailsView"></div>
        <div class="dataNotFoundMessage" style="margin-top: 10%; margin-left: 9%;"></div>
        
        <div class="ledgerDataView" style="display: none;">
            <p class="ledgerDetails"></p>
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
                <tbody id="LdgrTableBody">
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