<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsValues extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create("setting_values", function (Blueprint $table) {
			$table->increments("id");
			$table->string("value")->nullable();
			$table->integer("setting_id")->unsigned();
			$table->foreign("setting_id")->references("id")->on('settings');
			$table->integer("user_id")->unsigned();
			$table->foreign("user_id")->references("id")->on('users')->onDelete("cascade");
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
		//
	}

}
