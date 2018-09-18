
<?php $__env->startSection('page_heading','Trial Balance List'); ?>
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

            <form class="form-horizontal" method="post" id="TrailbalanceForm">

                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">

                <div class="col-md-10 row">
                    <div class="col-md-6 row">
                        <div class="col-md-3">
                            Form:
                        </div>
                        <div class="col-md-9">
                            <input type="date" class="form-control" placeholder="Search date...." name="TrailbalanceDateSearchFldFrom" id="TrailbalanceDateSearchFldFrom" value="<?php echo e(Session::get('Trailbalance_fromDate')); ?>"  >
                            <p id="search"></p>
                        </div>
                    </div>

                    <div class="col-md-6 row">
                        <div class="col-md-3">
                            To:
                        </div>
                        <div class="col-md-9">
                            <input type="date" class="form-control" placeholder="Search date...." name="TrailbalanceDateSearchFldTo" id="TrailbalanceDateSearchFldTo" value="<?php echo e(Session::get('Trailbalance_toDate')); ?>" >
                            <p id="search"></p>
                        </div>
                    </div>
                </div>

            
                <div class="col-md-2">
                    <input class="form-control btn btn-primary btn-outline" type="submit" value="search" id="generalTrailbalanceSearchBtn">
                </div>
            </form>
        </div>

        <div class="preLoadImageView" style="display: none;">
            <center>
                <img src="<?php echo e(asset("assets/images/preLoader_2.gif")); ?>">
            </center>
        </div>
        <div class="TrailbalanceDetailsView"></div>
        <div class="dataNotFoundMessage" style="margin-top: 10%; margin-left: 9%;"></div>
        
        <div class="TrailbalanceDataView" style="display: none;">
            <p class="dateFrom"></p> 
            <p class="dateTo"></p>
            <table class="table table-bordered table-striped">
                <thead>
                    
                    <tr>
                        <th>Account Name</th>
                        <th>Debit of Closing Balance</th>
                        <th>Credit of Closing Balance</th>
                    </tr>
                </thead>
                <tbody id="TrailbalanceTableBody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo e(asset("js/trialBalance.js")); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>