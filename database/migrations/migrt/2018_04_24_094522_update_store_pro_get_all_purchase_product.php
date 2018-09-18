<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStoreProGetAllPurchaseProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP procedure IF EXISTS get_all_purchase_products");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_purchase_products`(IN `grp_id` INT(11), IN `comp_id` INT(11),IN `startedAt` INT(11), IN `limits` INT(11))
SELECT mpp.*,
 pr.name as product_name,
 pk.name as packet_name,
 pk.quantity as packet_quantity,
 u.name as unit_name,
 pk.unit_quantity,
 mu.first_name as client_name,
 mu.address as client_address,
 mi.invoice_code,
 mtvc.total_amount_with_vat,
 GROUP_CONCAT(mtvc.calculate_amount) as new_vat_tax,
 GROUP_CONCAT(mt.name) as vat_tax_name,
 GROUP_CONCAT(mtvc.vat_tax_id) as vat_tax_id
 
 FROM mxp_product_purchase mpp
 inner join mxp_product pr on(mpp.product_id = pr.id)
 inner JOIN mxp_packet pk ON(pr.packing_id=pk.id)
 inner join mxp_unit u ON(u.id=pk.unit_id)
 inner join mxp_users mu on(mpp.client_id=mu.user_id)
 inner join mxp_invoice mi on(mpp.invoice_id=mi.id)
 inner join mxp_taxvat_cals mtvc on(mtvc.invoice_id = mpp.invoice_id
    AND mtvc.product_id = mpp.product_id
    AND mpp.id = mtvc.sale_purchase_id)
 inner join mxp_taxvats mt on(mtvc.vat_tax_id=mt.id)
    
 
 WHERE mpp.com_group_id=grp_id
 AND mpp.company_id=comp_id
 AND mpp.is_deleted=0 
 group by mtvc.sale_purchase_id
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
        DB::unprepared("DROP procedure IF EXISTS get_all_purchase_products");
    }
}
