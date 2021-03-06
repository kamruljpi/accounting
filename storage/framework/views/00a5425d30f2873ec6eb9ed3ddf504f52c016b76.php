<?php $__env->startSection('page_heading',trans('others.mxp_menu_product')); ?>
<?php $__env->startSection('section'); ?>

<?php $__env->startSection('section'); ?>
	<div class="col-md-10 col-md-offset-1">
		<div>
			<a href="<?php echo e(Route('add_product_entry_view')); ?>"> <?php echo $__env->make('widgets.button', array('class'=>'success', 'value'=>trans('others.heading_add_new_packet_label')), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?></a><br><br>
		</div>

		<?php echo $__env->make('layouts.pagination',['route_name'=>'entry_product_list_view'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

		<div>
			<table class="table table-bordered">
	            <thead>
	                <tr>
                        <th class=""><?php echo e(trans('others.serial_no_label')); ?></th>
                        <th class=""><?php echo e(trans('others.packet_details_label')); ?></th>
                        <th class=""><?php echo e(trans('others.product_group_label')); ?></th>
                        <th class=""><?php echo e(trans('others.status_label')); ?></th>
                        <th class=""><?php echo e(trans('others.action_label')); ?></th>
	                </tr>
	            </thead>
	            <tbody>
	            	<?php ($i = 1); ?>
		            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e($i++); ?></td>
							<td><?php echo e($product->packet_name); ?></td>
							<td><?php echo e($product->pord_grp_name); ?></td>
							<td><?php echo e(($product->is_active == 1)? trans('others.action_active_label'):trans('others.action_inactive_label')); ?></td>
							<td>
								<a href="<?php echo e(Route('edit_product_entry_view')); ?>/<?php echo e($product->group_id); ?>"> <?php echo $__env->make('widgets.button', array('class'=>'success', 'value'=>trans('others.edit_button')), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?></a>
								<a class="delete_id" href="<?php echo e(Route('delete_product_entry_action')); ?>/<?php echo e($product->id); ?>"> <?php echo $__env->make('widgets.button', array('class'=>'danger', 'value'=>trans('others.delete_button')), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> </a>
							</td>
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	            </tbody>
	        </table>
		</div>
	</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>