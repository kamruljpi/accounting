<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColPropMxpJournalPosting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mxp_journal_posting', function (Blueprint $table) 
        {
            $table->integer('transaction_type_id')->nullable()->change();
            $table->integer('account_head_id')->nullable()->change();
            $table->integer('sales_man_id')->nullable()->change();
            $table->integer('client_id')->nullable()->change();
            $table->integer('product_id')->nullable()->change();
            $table->integer('bank_id')->nullable()->change();
            $table->integer('branch_id')->nullable()->change();
            $table->integer('reference_id')->nullable()->change();
            $table->string('reference_no')->nullable()->change();
            $table->string('cf_code')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mxp_journal_posting');
    }
}
