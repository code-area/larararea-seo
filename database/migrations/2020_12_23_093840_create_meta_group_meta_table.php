<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMetaGroupMetaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('meta_group_meta', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('meta_id')->unsigned()->nullable()->index('meta_group_meta_meta_id_foreign');
			$table->integer('meta_group_id')->unsigned()->nullable()->index('meta_group_meta_meta_group_id_foreign');
			$table->string('default_content', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('meta_group_meta');
	}

}
