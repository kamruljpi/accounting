<?php $__env->startSection('page_heading',trans('others.heading_update_product_label')); ?>
<?php $__env->startSection('section'); ?>

<?php $__env->startSection('section'); ?>
    <div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading"><?php echo e(trans('others.edit_product_label')); ?></div>
					<div class="panel-body">

						<form class="form-horizontal" role="form" method="POST" action="<?php echo e(Route('edit_product_action')); ?>">
							<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
							<input type="hidden" name="id" value="<?php echo e($product[0]->id); ?>">
							<input type="hidden" name="group_id" value="<?php echo e($product[0]->group_id); ?>">

							<?php if($errors->any()): ?>
							    <div class="alert alert-danger">
							        <ul>
							            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							                <li><?php echo e($error); ?></li>
							            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							        </ul>
							    </div>
							<?php endif; ?>

							<div class="form-group">
								<label class="col-md-4 control-label"><?php echo e(trans('others.product_group_label')); ?></label>
								<div class="col-md-6">
									<select class="form-control input_required" name="p_group" required >
                                        <?php $__currentLoopData = $productGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($productGroup->id); ?>" <?php echo e(($productGroup->id == $product[0]->product_group_id)? "selected":""); ?>><?php echo e($productGroup->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label"><?php echo e(trans('others.product_name_label')); ?></label>
								<div class="col-md-6">
									<input type="text" class="form-control input_required" name="name" autocomplete="off" value="<?php echo e($product[0]->name); ?>">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label"><?php echo e(trans('others.packet_details_label')); ?></label>
								<div class="col-md-6">
									<select style="width:100%" name="packet_ids[]" class="selections" multiple="multiple">
				                        <?php $__currentLoopData = $packets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $packet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				                            <option value="<?php echo e($packet->id); ?>" <?php echo e((in_array($packet->id, $productPaketList)? 'selected':'')); ?>><?php echo e($packet->name.'('.$packet->quantity.')    '.$packet->unit_name.'('.$packet->unit_quantity.')'); ?></option>
				                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				                    </select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label"><?php echo e(trans('others.product_code_label')); ?></label>
								<div class="col-md-6">
									<input type="text" class="form-control input_required" name="code" autocomplete="off" value="<?php 
										$codes = explode('_', $product[0]->product_code);
										echo $codes[2];
									 ?>">
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-3 col-md-offset-4">
									<div class="select">
										<select class="form-control" type="select" name="isActive" >
											<option  value="1" name="isActive" <?php echo e(($product[0]->group_id == 1)? 'selected' : ''); ?>><?php echo e(trans('others.action_active_label')); ?></option>
											<option value="0" name="isActive" <?php echo e(($product[0]->group_id == 0) ? 'selected' : ''); ?>><?php echo e(trans('others.action_inactive_label')); ?></option>
						   				</select>
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<button type="submit" class="btn btn-primary" style="margin-right: 15px;">
										<?php echo e(trans('others.update_button')); ?>

									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

    <script type="text/javascript">
        $(".selections").select2();
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>