<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGetAllSaleProductPro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP procedure IF EXISTS get_all_sales_product");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_sales_product`(IN `grp_id` INT(11), IN `comp_id` INT(11), IN `startedAt` INT(11), IN `limits` INT(11))
SELECT 
mp.name as product_name,
mpk.name as packet_name,
mpk.quantity as packet_quantity,
munit.name as unit_name,
mpk.unit_quantity,
mu.acc_final_name as client_name, 
msp.quantity, 
msp.price, 
mi.invoice_code, 
mt.transport_name, 
msp.sale_date,
GROUP_CONCAT(mtvc.calculate_amount) as new_vat_tax,
GROUP_CONCAT(mtv.name) as vat_tax_name,
GROUP_CONCAT(mtvc.vat_tax_id) as vat_tax_id
from mxp_sale_products msp 
LEFT JOIN mxp_product mp ON mp.id = msp.product_id
LEFT JOIN mxp_invoice mi ON mi.id = msp.invoice_id
LEFT join mxp_chart_of_acc_heads mu on msp.client_id=mu.chart_o_acc_head_id
LEFT JOIN mxp_transports mt ON mt.invoice_id = msp.invoice_id
LEFT JOIN mxp_packet mpk ON mpk.id = mp.packing_id
LEFT JOIN mxp_unit munit ON munit.id = mpk.unit_id 
LEFT JOIN mxp_taxvat_cals mtvc ON mtvc.invoice_id = msp.invoice_id
LEFT JOIN mxp_taxvats mtv ON mtvc.vat_tax_id=mtv.id
WHERE msp.com_group_id=grp_id
AND msp.company_id=comp_id
AND msp.is_deleted=0
GROUP BY mtvc.sale_purchase_id
limit startedAt,limits;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP procedure IF EXISTS get_all_sales_product");
    }
}
