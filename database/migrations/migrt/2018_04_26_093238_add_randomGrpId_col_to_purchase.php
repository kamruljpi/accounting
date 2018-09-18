<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRandomGrpIdColToPurchase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mxp_product_purchase', function($table) {
            $table->integer('jouranl_posting_rand_grp_id')->after('purchase_date');
        });
        Schema::table('mxp_lc_purchases', function($table) {
            $table->integer('jouranl_posting_rand_grp_id')->after('purchase_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mxp_product_purchase');
        Schema::dropIfExists('mxp_lc_purchases');
    }
}
