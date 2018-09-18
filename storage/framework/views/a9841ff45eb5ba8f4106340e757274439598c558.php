<?php $__env->startSection('page_heading','Journal Posting List'); ?>
<?php $__env->startSection('section'); ?>

<style type="text/css">
    .panel-heading{
        display: none;
    }
    .panel-body{
        padding: 0px;
    }
</style>

<?php if(Session::has('journal_posting_status')): ?>
    <?php echo $__env->make('widgets.alert', array('class'=>'success', 'message'=> Session::get('journal_posting_status') ), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php endif; ?>


<div class="create_form_btn" style="margin-bottom: 20px;">
    <a href="<?php echo e(Route('journal_posting_form_view')); ?>">
        <button class="btn btn-success"><?php echo e(trans('others.add_journal_posting_label')); ?></button>
    </a>
</div>


<div class="col-sm-12">
    <div>


        <?php $__env->startSection('cotable_panel_body'); ?>

            <?php if(Session::has('role_delete_msg')): ?>
                <?php echo $__env->make('widgets.alert', array('class'=>'danger', 'message'=> Session::get('role_delete_msg') ), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endif; ?>
            <?php if(Session::has('role_update_msg')): ?>
                <?php echo $__env->make('widgets.alert', array('class'=>'success', 'message'=> Session::get('role_update_msg') ), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endif; ?>
            <?php if(Session::has('company_delete')): ?>
                <?php echo $__env->make('widgets.alert', array('class'=>'danger', 'message'=> Session::get('company_delete') ), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endif; ?>


            <div class="text-center">
                <?php echo e($get_all_journal->links()); ?>

            </div>

           <!--  <div class="input-group add-on">
              <input class="form-control" placeholder="<?php echo e(trans('others.search_placeholder')); ?>" name="srch-term" id="user_search" type="text">
              <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
              </div>
            </div> -->
            <br>


            <div class="input-group">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><?php echo e(trans('others.serial_no_label')); ?></th>
                        <th><?php echo e(trans('others.mxp_date')); ?></th>
                        <th><?php echo e(trans('others.mxp_particulars')); ?></th>
                        <th><?php echo e(trans('others.mxp_credit')); ?></th>
                        <th><?php echo e(trans('others.mxp_debit')); ?></th>
                        <th><?php echo e(trans('others.status_label')); ?></th>

                    </tr>
                </thead>
                <tbody>
                    <?php ($i = 1); ?>
                    <?php $__currentLoopData = $get_all_journal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $all_list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($i++); ?></td>
                        <td><?php echo e($all_list->journal_date); ?></td>
                        <td><?php echo e($all_list->particular); ?></td>
                        <td><?php echo e($all_list->transaction_amount_credit); ?></td>
                        <td><?php echo e($all_list->transaction_amount_debit); ?></td>
                        <td><?php echo e($all_list->is_active); ?></td>
                        

                            

                        
                    </tr>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            </div>
        <?php $__env->stopSection(); ?>
        <?php echo $__env->make('widgets.panel', array('header'=>true, 'as'=>'cotable'), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>