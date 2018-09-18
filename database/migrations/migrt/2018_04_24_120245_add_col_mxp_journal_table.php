<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColMxpJournalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mxp_journal_posting', function($table) {
            $table->integer('client_id')->after('transaction_amount_credit');
            $table->integer('product_id')->after('client_id');
            $table->integer('bank_id')->after('product_id');
            $table->integer('branch_id')->after('bank_id');
            $table->date('journal_date')->after('branch_id');
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
