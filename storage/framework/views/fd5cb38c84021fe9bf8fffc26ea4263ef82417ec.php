
<?php $__env->startSection('section'); ?>

<div>
<button onclick='printDiv("page-wrap");' type="button" style="margin:0 0 10px 778px" class="btn btn-info print_y" data-dismiss="modal"><?php echo e(trans('others.mxp_print_btn')); ?></button>
</div>	
	<div id="page-wrap">

		<h1 id="header">INVOICE</h1>
		
		<div id="identity">
		
            <p id="address">MarproITSolutions
123 Appleseed
Appleville, WI 53719

Phone:</p>

            <div id="logo">
              <img id="image" src="<?php echo e(asset("assets/images/chemax-logo.png")); ?>" alt="logo" width="100px" height="50px">
              
            </div>
		
		</div>
		
		<div style="clear:both"></div>
		
		<div id="customer">
            <h3>Customer</h3>
            <p id="customer-title"><?php echo e($invoices_op[0]->client_name); ?></p>

            <table id="meta">
            	<tr>
                    <td class="meta-head">Order No</td>
                    <td><span><?php echo e($invoices_op[0]->order_no); ?></span></td>
                </tr>
                <tr>
                    <td class="meta-head">Invoice #</td>
                    <td><span><?php echo e($invoices_op[0]->invoice_code); ?></span></td>
                </tr>
                <tr>

                    <td class="meta-head">Date</td>
                    <td><span id="date"><?php echo e($invoices_op[0]->created_at); ?></span></td>
                </tr>
                <tr>
                    <td class="meta-head">Amount Due</td>
                    <td><div class="due"><?php echo e($invoices_op[0]->due_ammount); ?></div></td>
                </tr>

            </table>
		
		</div>
		
		<table id="items">
		
		  <tr>
		      <th>Item</th>
		      <th>Description</th>
		      <th>Unit Cost</th>
		      <th>Quantity</th>
		      <th>Bonus</th>
		      <th>Price</th>
		  </tr>
<?php $rt=0;$vati=0;$total_w_vat=0;$paid=0?>		  
<?php $__currentLoopData = $invoices_op; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php $rt+=$row->price*$row->quantity;
$vati += $row->vat;
$total_w_vat += $row->total_amount_w_vat;
$paid+=$row->total_amount_w_vat;
?>
<tr class="item-row">
		      <td class="item-name"><div class="delete-wpr"><span><?php echo e($row->product_name); ?>-<?php echo e($row->packet_name); ?>(<?php echo e($row->packetsqty); ?>)<?php echo e($row->unit_quantity); ?><?php echo e($row->unit_name); ?></span></div></td>
		      <td class="description"><span></span></td>
		      <td><span class="cost"><?php echo e($row->price); ?></span></td>
		      <td><span class="qty"><?php echo e($row->quantity); ?></span></td>
		      <td><span class="qty"><?php echo e($row->bonus); ?></span></td>
		      <td><span class="price"><?php echo e($row->price*$row->quantity); ?></span></td>
		  </tr>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				  <tr id="hiderow">
				    <td colspan="5"><a id="addrow" href="javascript:;"></a></td>
				  </tr>
				  
				  <tr>
				      <td colspan="2" class="blank"> </td>
				      <td colspan="2" class="total-line">Subtotal</td>
				      <td class="total-value"><div id="subtotal"><?php echo e($rt); ?></div></td>
				  </tr>
				  <tr>

				      <td colspan="2" class="blank"> </td>
				      <td colspan="2" class="total-line">Total Vat</td>
				      <td class="total-value"><div id="total"><?php echo e($vati); ?> Taka</div></td>
				  </tr>
				  <tr>

				      <td colspan="2" class="blank"> </td>
				      <td colspan="2" class="total-line">Total With Vat</td>
				      <td class="total-value"><div id="total"><?php echo e($rt+$vati); ?> Taka</div></td>
				  </tr>
				  <tr>
				      <td colspan="2" class="blank"> </td>
				      <td colspan="2" class="total-line">Amount Paid</td>

				      <td class="total-value"><span id="paid"><?php echo e($total_w_vat); ?></span></td>
				  </tr>
				  <tr>
				      <td colspan="2" class="blank"> </td>
				      <td colspan="2" class="total-line balance">Balance Due</td>
				      <td class="total-value balance"><div class="due"><?php echo e($invoices_op[0]->due_ammount); ?></div></td>
				  </tr>
				   
		</table>
		<h5>(paid amount)In word:<?php  $word=App\Http\Controllers\sale\SaleManagement::getnumbertoWord($total_w_vat);
		print_r($word);?>Taka Only.</h5>
		
		<div id="terms">
		  <h5>Terms</h5>
		  <p></p>
		</div>
	
	</div>
	
	<?php $__env->stopSection(); ?>
	<style type="text/css">
/*
	 CSS-Tricks Example
	 by Chris Coyier
	 http://css-tricks.com
*/

* { margin: 0; padding: 0; }
body { font: 14px/1.4 Georgia, serif; }
#page-wrap { width: 800px; margin: 0 auto; }

textarea { border: 0; font: 14px Georgia, Serif; overflow: hidden; resize: none; }
table { border-collapse: collapse; }
table td, table th { border: 1px solid black; padding: 5px; }

#header { height: 30px; width: 100%; margin: 20px 0; background: #222; text-align: center; color: white; font: bold 15px Helvetica, Sans-Serif; text-decoration: uppercase; letter-spacing: 20px; padding: 8px 0px; }

#address { width: 250px; height: 150px; float: left; }
#customer { overflow: hidden; }

#logo { text-align: right; float: right; position: relative; margin-top: 25px; max-width: 540px; max-height: 100px; }
#logoctr { display: none; }
#logo:hover #logoctr, #logo.edit #logoctr { display: block; text-align: right; line-height: 25px; background: #eee; padding: 0 5px; }
#logohelp { text-align: left; display: none; font-style: italic; padding: 10px 5px;}
#logohelp input { margin-bottom: 5px; }
.edit #logohelp { display: block; }
.edit #save-logo, .edit #cancel-logo { display: inline; }
.edit #image, #save-logo, #cancel-logo, .edit #change-logo, .edit #delete-logo { display: none; }
#customer-title { font-size: 20px; font-weight: bold; float: left; }

#meta { margin-top: 1px; width: 300px; float: right; }
#meta td { text-align: right;  }
#meta td.meta-head { text-align: left; background: #eee; }
#meta td textarea { width: 100%; height: 20px; text-align: right; }

#items { clear: both; width: 100%; margin: 30px 0 0 0; border: 1px solid black; }
#items th { background: #eee; }
#items textarea { width: 80px; height: 50px; }
#items tr.item-row td { border: 1; vertical-align: top; }
#items td.description { width: 300px; }
#items td.item-name { width: 175px; }
#items td.description textarea, #items td.item-name textarea { width: 100%; }
#items td.total-line { border-right: 0; text-align: right; }
#items td.total-value { border-left: 0; padding: 10px; }
#items td.total-value textarea { height: 20px; background: none; }
#items td.balance { background: #eee; }
#items td.blank { border: 0; }

#terms { text-align: center; margin: 20px 0 0 0; }
#terms h5 { text-transform: uppercase; font: 13px Helvetica, Sans-Serif; letter-spacing: 10px; border-bottom: 1px solid black; padding: 0 0 8px 0; margin: 0 0 8px 0; }
#terms textarea { width: 100%; text-align: center;}

textarea:hover, textarea:focus, #items td.total-value textarea:hover, #items td.total-value textarea:focus, .delete:hover { background-color:#EEFF88; }

.delete-wpr { position: relative; }
.delete { display: block; color: #000; text-decoration: none; position: absolute; background: #EEEEEE; font-weight: bold; padding: 0px 3px; border: 1px solid; top: -6px; left: -22px; font-family: Verdana; font-size: 12px; }
	</style>

<?php echo $__env->make('layouts.dashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>