<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumntToLcPurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mxp_lc_purchases', function($table) {
            $table->integer('product_id');
            $table->integer('invoice_id');
            $table->integer('client_id');
            $table->integer('com_group_id');
            $table->integer('company_id');
            $table->integer('user_id');
            $table->string('quantity');
            $table->string('price');
            $table->integer('bonus');
            $table->tinyInteger('stock_status');
            $table->date('purchase_date');
            $table->tinyInteger('is_deleted');
            $table->tinyInteger('lc_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mxp_lc_purchases');
    }
}
