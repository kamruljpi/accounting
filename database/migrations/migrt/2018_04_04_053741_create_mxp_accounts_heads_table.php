<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMxpAccountsHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mxp_accounts_heads', function (Blueprint $table) {
            $table->increments('accounts_heads_id');
            $table->string('head_name_type');
            $table->string('account_code');
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
        Schema::dropIfExists('mxp_accounts_heads');
    }
}
