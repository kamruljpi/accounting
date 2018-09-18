<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMxpLcPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mxp_lc_purchases', function (Blueprint $table) {
            $table->increments('lc_purchase_id');
            $table->string('lc_no');
            $table->string('lc_type');
            $table->integer('bank_swift_charge');
            $table->integer('vat');
            $table->integer('bank_commission');
            $table->integer('apk_form_charge');
            $table->integer('lc_margin');
            $table->integer('lc_amount_usd');
            $table->integer('lc_amount_taka');
            $table->integer('due_payment');
            $table->integer('others_cost');
            $table->integer('total_amount_with_other_cost');
            $table->integer('total_amount_without_other_cost');
            $table->integer('dollar_rate');
            $table->timestamps();
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
