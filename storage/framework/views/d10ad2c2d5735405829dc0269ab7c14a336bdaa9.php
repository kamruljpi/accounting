<?php $__env->startSection('page_heading',trans('others.heading_add_stock_label')); ?>
<?php $__env->startSection('section'); ?>

<?php $__env->startSection('section'); ?>
	<div>
		<div  >
			<div class="">
				<a href="<?php echo e(Route('update_stocks_view')); ?>"> <?php echo $__env->make('widgets.button', array('class'=>'info', 'value'=> trans('others.mxp_menu_update_stocks_action') ), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> </a>
			</div>
		</div>
		<br>

		

		<h4>Local Purchase Products</h4>
		<div>
			<table class="table table-bordered">
	            <thead>
	                <tr>
                        <th><?php echo e(trans('others.serial_no_label')); ?></th>
						<th><?php echo e(trans('others.product_name_label')); ?></th>
						<th><?php echo e(trans('others.product_group_label')); ?></th>
						<th><?php echo e(trans('others.quantity_label')); ?></th>
						<th><?php echo e(trans('others.unit_label')); ?></th>
                        <th><?php echo e(trans('others.company_name_label')); ?></th>
						<th><?php echo e(trans('others.mxp_menu_store')); ?></th>
						<th><?php echo e(trans('others.action_label')); ?></th>
	                </tr>
	            </thead>
	            <tbody>
	            	<?php ($i = 1); ?> 
		            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e($i++); ?></td>
							<td><?php echo e($product->product_name.' ('.$product->packet_name.'('.$product->packet_quantity.')	'.$product->unit_name.'('.$product->unit_quantity.'))'); ?></td>
							<td><?php echo e($product->product_group); ?></td>
							<td><?php echo e($product->quantity); ?></td>
							<td><?php echo e($product->unit_name); ?></td>
							<td><?php echo e($product->client_name); ?></td>

							<form role="form" action="<?php echo e(Route('save_stock_action')); ?>" method="post">

                				<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                				<input type="hidden" name="local_pro_id" value="<?php echo e($product->id); ?>">

			                    <td>
									<select class="form-control input_required" name="location" required >
	                                    <option value=""><?php echo e(trans('others.option_select_location_label')); ?></option>
	                                    <?php $__currentLoopData = $stock_states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock_state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                                        <option value="<?php echo e($stock_state->id); ?>"><?php echo e($stock_state->name); ?></option>
	                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	                                </select>
	                            </td>
	                            <td>
	                            	<input style="float: left; width: 100% !important" class="form-control btn btn-warning" type="submit" value="<?php echo e(trans('others.save_button')); ?>" >
	                            </td>
			            	</form>
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	            </tbody>
	        </table>
		</div>

		<h4>L/C Purchase Products</h4>
		<div>
			<table class="table table-bordered">
				<thead>
				<tr>
					<th><?php echo e(trans('others.serial_no_label')); ?></th>
					<th><?php echo e(trans('others.product_name_label')); ?></th>
					<th><?php echo e(trans('others.product_group_label')); ?></th>
					<th><?php echo e(trans('others.quantity_label')); ?></th>
					<th><?php echo e(trans('others.unit_label')); ?></th>
					<th><?php echo e(trans('others.company_name_label')); ?></th>
					<th><?php echo e(trans('others.mxp_menu_store')); ?></th>
					<th><?php echo e(trans('others.action_label')); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php ($i = 1); ?>
				<?php $__currentLoopData = $lcproducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr>
						<td><?php echo e($i++); ?></td>
						<td><?php echo e($product->product_details); ?></td>
						<td><?php echo e($product->product_group); ?></td>
						<td><?php echo e($product->quantity); ?></td>
						<td><?php echo e($product->unit); ?></td>
						<td><?php echo e($product->client_details); ?></td>

						<form role="form" action="<?php echo e(Route('save_stock_action')); ?>" method="post">

							<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
							<input type="hidden" name="lc_purchase_id" value="<?php echo e($product->lc_purchase_id); ?>">

							<td>
								<select class="form-control input_required" name="location" required >
									<option value=""><?php echo e(trans('others.option_select_location_label')); ?></option>
									<?php $__currentLoopData = $stock_states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock_state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<option value="<?php echo e($stock_state->id); ?>"><?php echo e($stock_state->name); ?></option>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</select>
							</td>
							<td>
								<input style="float: left; width: 100% !important" class="form-control btn btn-warning" type="submit" value="<?php echo e(trans('others.save_button')); ?>" >
							</td>
						</form>
					</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</tbody>
			</table>
		</div>
	</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>