<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreProcedureGetAllStocksByProductGroupId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_stocks_by_productGrpId`(IN `grp_id` INT, IN `comp_id` INT, IN `pro_grp_id` INT) 
Select mpr.id as pro_id, 
mpr.name as product_name, 
mpr.product_code, 
pk.name as packet_name, 
pk.quantity as packet_quantity, 
u.name as unit_name, 
pk.unit_quantity, 
pg.name as product_group, 
mu.first_name as client_name, 
mu.address 

from mxp_product_purchase pp 
inner join mxp_users mu on (pp.user_id = mu.user_id) 
inner join mxp_product mpr on(pp.product_id = mpr.id) 
inner JOIN mxp_packet pk ON(mpr.packing_id=pk.id) 
inner JOIN mxp_product_group pg on( mpr.product_group_id=pg.id) 
inner join mxp_unit u ON(u.id=pk.unit_id) 

WHERE pp.com_group_id=grp_id
AND pp.company_id=comp_id 
AND mpr.product_group_id=pro_grp_id 
AND pp.is_active=1 
AND pp.is_deleted=0 AND pp.stock_status=1
GROUP BY mpr.id;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP procedure IF EXISTS get_all_stocks_by_productGrpId");
    }
}
