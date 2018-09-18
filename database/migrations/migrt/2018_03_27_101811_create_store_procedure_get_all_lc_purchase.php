<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreProcedureGetAllLcPurchase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_lc_purchase_products`(IN `grp_id` INT(11), IN `comp_id` INT(11), IN `user_id` INT(11))
            SELECT mlp.*,
            GROUP_CONCAT(pr.name,'(',pk.name,'(',pk.quantity,') ',un.name,'(',pk.unit_quantity,'))') as product_details,
            GROUP_CONCAT(mu.first_name,'(',mu.address,')') as client_details

            FROM mxp_lc_purchases mlp
            inner join mxp_product pr on(mlp.product_id = pr.id)
            inner JOIN mxp_packet pk ON(pr.packing_id=pk.id)
            inner join mxp_unit un ON(un.id=pk.unit_id)
            inner join mxp_users mu on(mlp.client_id=mu.user_id)

            WHERE mlp.com_group_id=grp_id
            AND mlp.company_id=comp_id
            AND mlp.user_id=user_id 
            AND mlp.is_deleted=0
            GROUP BY mlp.lc_purchase_id;"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP procedure IF EXISTS get_all_lc_purchase_products");
    }
}
