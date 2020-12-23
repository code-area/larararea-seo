<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMetasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('metas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('is_required')->default(0);
			$table->integer('min')->nullable();
			$table->integer('max')->nullable();
			$table->boolean('is_required_in_group')->default(0);
			$table->smallInteger('is_active')->nullable();
			$table->smallInteger('only_in_groups')->nullable()->default(0);
			$table->string('attribute', 191)->comment('values is property, name');
			$table->string('attribute_value', 191)->nullable();
			$table->string('default_content', 191)->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('metas');
	}

}
