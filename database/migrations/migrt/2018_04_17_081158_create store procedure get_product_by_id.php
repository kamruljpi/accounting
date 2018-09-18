<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreProcedureGetProductById extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_productDetails_by_id`(IN `product_id` INT(11))
SELECT CONCAT(mp.name,'(',mpk.name,'(',mpk.quantity,')',mu.name,'(',mpk.unit_quantity,'))') as product_details

FROM mxp_product mp
inner join mxp_packet mpk on(mpk.id = mp.packing_id)
inner join mxp_unit mu on(mu.id = mpk.unit_id)

WHERE mp.id = product_id;"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP procedure IF EXISTS get_productDetails_by_id");
    }
}
