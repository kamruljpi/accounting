<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGetPurchaseProductByIdPro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP procedure IF EXISTS get_purchase_productBy_id");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_purchase_productBy_id`(IN `purchase_product_id` INT(11))
SELECT mpp.*,
 pr.name as product_name,
 pk.name as packet_name,
 pk.quantity as packet_quantity,
 u.name as unit_name,
 pk.unit_quantity,
 mu.acc_final_name as client_name,
 mi.invoice_code,
 GROUP_CONCAT(mtvc.calculate_amount) as new_vat_tax,
 GROUP_CONCAT(mt.name) as vat_tax_name,
 GROUP_CONCAT(mtvc.vat_tax_id) as vat_tax_id
 
 FROM mxp_product_purchase mpp
 inner join mxp_product pr on(mpp.product_id = pr.id)
 inner JOIN mxp_packet pk ON(pr.packing_id=pk.id)
 inner join mxp_unit u ON(u.id=pk.unit_id)
 inner join mxp_chart_of_acc_heads mu on(mpp.client_id=mu.chart_o_acc_head_id)
 inner join mxp_invoice mi on(mpp.invoice_id=mi.id)
 inner join mxp_taxvat_cals mtvc on(mtvc.invoice_id = mpp.invoice_id
	AND mtvc.product_id = mpp.product_id
    AND mpp.id = mtvc.sale_purchase_id)
 inner join mxp_taxvats mt on(mtvc.vat_tax_id=mt.id)
    
 
 WHERE mpp.id=purchase_product_id
 group by mtvc.sale_purchase_id;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP procedure IF EXISTS get_purchase_productBy_id");
    }
}
