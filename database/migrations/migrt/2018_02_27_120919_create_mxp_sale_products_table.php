<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMxpSaleProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mxp_sale_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('invoice_id');
            $table->integer('client_id');
            $table->integer('com_group_id');
            $table->integer('company_id');
            $table->integer('user_id');
            $table->string('quantity');
            $table->string('price');
            $table->integer('bonus');
            $table->string('commission');
            $table->date('sale_date');
            $table->tinyInteger('is_deleted');
            $table->tinyInteger('is_active');
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
        Schema::dropIfExists('mxp_sale_products');
    }
}
