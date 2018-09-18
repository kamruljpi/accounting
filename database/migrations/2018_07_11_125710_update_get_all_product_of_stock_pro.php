<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGetAllProductOfStockPro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP procedure IF EXISTS get_all_products_of_stock");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_products_of_stock`(IN `grp_id` INT(11), IN `comp_id` INT(11), IN `startedAt` INT(11), IN `limits` INT(11))
select st.*, 
pp.price, mu.acc_final_name as client_name, 
pg.name as product_group, 
pr.name as product_name, 
pk.name as packet_name,
pk.quantity as packet_quantity, 
u.name as unit_name, 
pk.unit_quantity

from mxp_stock st 
inner join mxp_product_purchase pp on(st.purchase_id = pp.id) 
inner join mxp_chart_of_acc_heads mu on (pp.client_id = mu.chart_o_acc_head_id)
inner join mxp_product pr on(st.product_id = pr.id) 
inner JOIN mxp_packet pk ON(pr.packing_id=pk.id) 
inner JOIN mxp_product_group pg on ( pr.product_group_id=pg.id) 
inner join mxp_unit u ON(u.id=pk.unit_id)

WHERE st.com_group_id=grp_id
    AND st.company_id=comp_id
    AND st.status=1
    AND st.is_deleted=0
order by st.id desc
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
