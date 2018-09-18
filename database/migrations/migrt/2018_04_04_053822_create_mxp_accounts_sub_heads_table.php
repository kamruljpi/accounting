<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMxpAccountsSubHeadsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('mxp_accounts_sub_heads', function (Blueprint $table) {
			$table->increments('accounts_sub_heads_id');
			$table->integer('accounts_heads_id')->unsigned();
			$table->string('sub_head');
			$table->integer('company_id');
			$table->integer('group_id');
			$table->integer('user_id');
			$table->tinyInteger('is_deleted');
			$table->tinyInteger('is_active');
			$table->timestamps();
		});

		Schema::table('mxp_accounts_sub_heads', function (Blueprint $table) {
			$table->foreign('accounts_heads_id')->references('accounts_heads_id')->on('mxp_accounts_heads');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('mxp_accounts_sub_heads');
	}
}
