<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGetAllStocksProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP procedure IF EXISTS get_all_stocks");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_stocks`(IN `grp_id` INT, IN `comp_id` INT)
select pp.*, 
mpr.name as product_name, 
pk.name as packet_name, 
pk.quantity as packet_quantity, 
u.name as unit_name, 
pk.unit_quantity, 
pg.name as product_group, 
mu.acc_final_name as client_name

from mxp_product_purchase pp 
inner join mxp_chart_of_acc_heads mu on(pp.client_id=mu.chart_o_acc_head_id)
inner join mxp_product mpr on(pp.product_id = mpr.id) 
inner JOIN mxp_packet pk ON(mpr.packing_id=pk.id) 
inner JOIN mxp_product_group pg on( mpr.product_group_id=pg.id) 
inner join mxp_unit u ON(u.id=pk.unit_id) 

WHERE pp.com_group_id=grp_id 
AND pp.company_id=comp_id 
AND pp.is_active=1 
AND pp.is_deleted=0 
AND pp.stock_status=0 
order by pp.id desc;"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP procedure IF EXISTS get_all_stocks");
    }
}
