<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeoUrisTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seo_uris', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->boolean('is_active')->default(1);
			$table->string('uri', 191);
			$table->string('route_name', 191);
			$table->timestamps();
			$table->boolean('use_route_tags')->default(1);
			$table->text('tags')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('seo_uris');
	}

}
