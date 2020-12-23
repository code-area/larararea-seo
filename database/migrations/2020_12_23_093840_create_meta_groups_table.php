<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMetaGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('meta_groups', function(Blueprint $table)
		{
			$table->increments('id');
			$table->smallInteger('is_active')->nullable();
			$table->string('starts_with', 191)->comment('values is twitter, open grapg');
			$table->string('headline', 191);
			$table->string('comment_start', 191)->nullable();
			$table->string('comment_end', 191)->nullable();
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
		Schema::drop('meta_groups');
	}

}
