<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStoreProcedueGetAllProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP procedure IF EXISTS get_all_products");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_products`(IN `grp_id` INT(11), IN `comp_id` INT(11))
SELECT pr.*, GROUP_CONCAT(pk.name,'(',pk.quantity,') ',u.name,'(',pk.unit_quantity,')') as packet_name, pg.name as pord_grp_name  FROM mxp_product pr
 inner JOIN mxp_packet pk ON(pr.packing_id=pk.id) inner JOIN mxp_product_group pg on(
 pr.product_group_id=pg.id)
 inner join mxp_unit u ON(u.id=pk.unit_id)
 WHERE pr.com_group_id=grp_id AND pr.company_id=comp_id
  AND pr.is_active=1 AND pr.is_deleted=0
 GROUP BY pr.id;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP procedure IF EXISTS get_all_products");
    }
}
