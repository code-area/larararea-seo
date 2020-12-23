<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeoConfigsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seo_configs', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->boolean('title_required')->nullable();
			$table->integer('title_min')->unsigned()->nullable();
			$table->integer('title_max')->unsigned()->nullable();
			$table->boolean('robots_required')->nullable();
			$table->integer('robots_min')->unsigned()->nullable();
			$table->integer('robots_max')->unsigned()->nullable();
			$table->boolean('description_required')->nullable();
			$table->integer('description_min')->unsigned()->nullable();
			$table->integer('description_max')->unsigned()->nullable();
			$table->boolean('keywords_required')->nullable();
			$table->integer('keywords_min')->unsigned()->nullable();
			$table->integer('keywords_max')->unsigned()->nullable();
			$table->smallInteger('is_prepend_title')->default(0);
			$table->smallInteger('is_append_title');
			$table->string('title_prepend_separator', 191)->nullable();
			$table->string('title_append_separator', 191)->nullable();
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
		Schema::drop('seo_configs');
	}

}
