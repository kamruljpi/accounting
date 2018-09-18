<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <?php echo e($product[0]->product_name.'( '.$product[0]->packet_name.'('.$product[0]->packet_quantity.')  '.$product[0]->unit_name.'('.$product[0]->unit_quantity.') )'); ?>

                </h3>
            </div>
            <?php 
                $all_vat_taxs = explode(',', $product[0]->new_vat_tax);
                $vat_tax_names = explode(',', $product[0]->vat_tax_name);
             ?>
            <div class="modal-body">
                <div class="row">
                    <?php ($totalVat = 0); ?>
                    <div class="col-md-6">
                        <p><strong>Client   :</strong>   <?php echo e($product[0]->client_name); ?></p>
                        <p><strong>Invoice Code   :</strong>   <?php echo e($product[0]->invoice_code); ?></p>
                        <?php $__currentLoopData = $vat_tax_names; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p><strong><?php echo e($value); ?>   :</strong>   <?php echo e($all_vat_taxs[$key]); ?></p>
                            <?php ($totalVat += $all_vat_taxs[$key]); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <p><strong>Total vat tax :</strong>   <?php echo e($totalVat); ?></p>
                        <p><strong>Bonus   :</strong>   <?php echo e($product[0]->bonus); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Stock status  :</strong>   <?php echo e(($product[0]->stock_status == 1)? "stocked":"not yet stocked"); ?></p>
                        <p><strong>Price  :</strong>   <?php echo e($product[0]->price); ?></p>
                        <p><strong>Quantity   :</strong>   <?php echo e($product[0]->quantity); ?></p>
                        <p><strong>Total price :</strong>   <?php echo e($product[0]->quantity*$product[0]->price); ?></p>
                        <p><strong>Total price with vat :</strong>   <?php echo e($product[0]->quantity*$product[0]->price+$totalVat); ?></p>
                        <p><strong>Paid Amount :</strong>   <?php echo e(($product[0]->quantity*$product[0]->price+$totalVat)-$product[0]->due_amount); ?></p>
                        <p><strong>Due amount :</strong>   <?php echo e($product[0]->due_amount); ?></p>
                        <p><strong>Chart of Account:</strong>   <?php echo e($chart_of_acc->acc_final_name); ?></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('product.print_btn')); ?></button>
            </div>
        </div>
    </div>
</div>
