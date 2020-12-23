<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeoRoutesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seo_routes', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->boolean('is_active');
			$table->string('name', 191);
			$table->string('uri', 191);
			$table->string('template', 191)->default('[%]');
			$table->text('tags')->nullable();
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
		Schema::drop('seo_routes');
	}

}
