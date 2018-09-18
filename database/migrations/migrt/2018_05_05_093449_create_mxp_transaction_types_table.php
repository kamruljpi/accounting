<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMxpTransactionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mxp_transaction_type', function (Blueprint $table) {
            $table->increments('transaction_type_id');
            $table->string('transaction_type_name');
            $table->integer('company_id');
            $table->integer('group_id');
            $table->integer('user_id');
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
        Schema::dropIfExists('mxp_transaction_type');
    }
}
