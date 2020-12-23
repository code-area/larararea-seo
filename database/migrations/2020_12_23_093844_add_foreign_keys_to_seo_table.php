<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSeoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('seo', function(Blueprint $table)
		{
			$table->foreign('parent_id')->references('id')->on('seo')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('seo', function(Blueprint $table)
		{
			$table->dropForeign('seo_parent_id_foreign');
		});
	}

}
