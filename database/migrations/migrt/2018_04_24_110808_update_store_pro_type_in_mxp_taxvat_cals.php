<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStoreProTypeInMxpTaxvatCals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP procedure IF EXISTS get_all_product_purchase_info");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_product_purchase_info`(IN `grp_id` INT(11), IN `comp_id` INT(11))
SELECT 
mpp.id, mpp.price, mpp.quantity,
mp.name as product_name,
mc.name as client_name,
mi.id,
GROUP_CONCAT(mtc.id) as txvid,
GROUP_CONCAT(mtc.total_amount) as total, 
GROUP_CONCAT(mtc.calculate_amount) as calculate,
GROUP_CONCAT(mt.name) as txvat_name
FROM mxp_product_purchase mpp

LEFT JOIN mxp_taxvat_cals  mtc
ON mtc.purchase_id = mpp.id 

LEFT JOIN mxp_product mp 
ON mp.id = mpp.product_id

LEFT JOIN mxp_invoice mi 
ON mi.id = mpp.invoice_id

LEFT JOIN mxp_companies mc
ON mc.id = mpp.company_i

LEFT JOIN mxp_taxvats mt
ON mt.id = mtc.vat_tax_id

WHERE mpp.com_group_id = 1 AND mpp.company_id = 0 AND mpp.is_deleted = 0 AND mpp.is_active =1 AND mtc.type ='purchase'
GROUP BY mtc.purchase_id
order by mtc.purchase_id desc;");

        DB::unprepared("DROP procedure IF EXISTS get_all_sales_product");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_sales_product`(IN `grp_id` INT(11), IN `comp_id` INT(11),IN `startedAt` INT(11), IN `limits` INT(11))
SELECT 
mp.name as product_name,
mpk.name as packet_name,
mpk.quantity as packet_quantity,
munit.name as unit_name,
mpk.unit_quantity,
mu.first_name as client_name, 
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
LEFT JOIN mxp_users mu ON mu.user_id = msp.client_id
LEFT JOIN mxp_transports mt ON mt.invoice_id = msp.invoice_id
LEFT JOIN mxp_packet mpk ON mpk.id = mp.packing_id
LEFT JOIN mxp_unit munit ON munit.id = mpk.unit_id 
LEFT JOIN mxp_taxvat_cals mtvc ON mtvc.invoice_id = msp.invoice_id
LEFT JOIN mxp_taxvats mtv ON mtvc.vat_tax_id=mtv.id
WHERE msp.com_group_id=grp_id
AND msp.company_id=comp_id
AND msp.is_deleted=0
AND mtvc.type='sale'
GROUP BY mtvc.sale_purchase_id
order by mtvc.sale_purchase_id desc
limit startedAt,limits;");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
