<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMxpJournalsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('mxp_journals', function (Blueprint $table) {
			$table->increments('id');
			$table->string('particular');
			$table->string('description');
			$table->float('debit', 12, 2);
			$table->float('credit', 12, 2);
			$table->integer('reference_id');
			$table->string('reference_no');
			$table->string('code');
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
	public function down() {
		Schema::dropIfExists('mxp_journals');
	}
}
