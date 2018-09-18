<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMxpAccClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mxp_acc_classes', function (Blueprint $table) {
            $table->increments('mxp_acc_classes_id');
            $table->string('head_class_name');
            $table->integer('accounts_heads_id')->unsigned();
            $table->integer('accounts_sub_heads_id')->unsigned();
            $table->integer('company_id');
            $table->integer('group_id');
            $table->integer('user_id');
            $table->tinyInteger('is_deleted');
            $table->tinyInteger('is_active');
            $table->timestamps();
        });

        Schema::table('mxp_acc_classes', function (Blueprint $table) {
            $table->foreign('accounts_heads_id')->references('accounts_heads_id')->on('mxp_accounts_heads');
            $table->foreign('accounts_sub_heads_id')->references('accounts_sub_heads_id')->on('mxp_accounts_sub_heads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mxp_acc_classes');
    }
}

