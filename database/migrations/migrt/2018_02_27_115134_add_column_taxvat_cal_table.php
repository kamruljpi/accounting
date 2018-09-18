<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTaxvatCalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mxp_taxvat_cals', function($table) {
            // $table->renameColumn('purchase_id', 'sale_purchase_id');
            $table->string('percent')->after('calculate_amount');
            $table->string('type')->after('purchase_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mxp_taxvat_cals');
    }
}
