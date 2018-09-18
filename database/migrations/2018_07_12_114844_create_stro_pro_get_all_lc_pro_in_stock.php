<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStroProGetAllLcProInStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_lc_pro_in_stocks`(IN `grp_id` INT, IN `comp_id` INT)
SELECT mlp.*,
GROUP_CONCAT(pr.name,'(',pk.name,'(',pk.quantity,') ',un.name,'(',pk.unit_quantity,'))') as product_details,
pg.name as product_group, 
ca.acc_final_name as client_details

FROM mxp_lc_purchases mlp
inner join mxp_product pr on(mlp.product_id = pr.id)
inner JOIN mxp_packet pk ON(pr.packing_id=pk.id)
inner join mxp_unit un ON(un.id=pk.unit_id)
inner JOIN mxp_product_group pg on( pr.product_group_id=pg.id) 
inner join mxp_chart_of_acc_heads ca on(ca.chart_o_acc_head_id=mlp.client_id)

WHERE mlp.com_group_id=grp_id
AND mlp.company_id=comp_id
AND mlp.is_deleted=0
AND mlp.stock_status=0 
GROUP BY mlp.lc_purchase_id
order by mlp.lc_purchase_id desc;"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP procedure IF EXISTS get_all_lc_pro_in_stocks");
    }
}
