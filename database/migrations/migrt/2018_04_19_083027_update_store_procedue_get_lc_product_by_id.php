<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStoreProcedueGetLcProductById extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP procedure IF EXISTS get_lc_purchase_product_by_id");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_lc_purchase_product_by_id`(IN `lc_product_id` INT(11))
SELECT mlp.*,
GROUP_CONCAT(pr.name,'(',pk.name,'(',pk.quantity,') ',un.name,'(',pk.unit_quantity,'))') as product_details,
GROUP_CONCAT(mu.first_name,'(',mu.address,')') as client_details,
mu.user_id,
pr.product_group_id,
pr.id as product_id,
mi.invoice_code,
mca.acc_final_name

FROM mxp_lc_purchases mlp
inner join mxp_product pr on(mlp.product_id = pr.id)
inner JOIN mxp_packet pk ON(pr.packing_id=pk.id)
inner join mxp_unit un ON(un.id=pk.unit_id)
inner join mxp_users mu on(mlp.client_id=mu.user_id)
inner join mxp_invoice mi on(mlp.invoice_id=mi.id)
inner join mxp_chart_of_acc_heads mca on(mca.chart_o_acc_head_id=mlp.account_head_id)

WHERE mlp.lc_purchase_id=lc_product_id
GROUP BY mlp.lc_purchase_id;");
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
