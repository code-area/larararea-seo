<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMetaGroupMetaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('meta_group_meta', function(Blueprint $table)
		{
			$table->foreign('meta_group_id')->references('id')->on('meta_groups')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('meta_id')->references('id')->on('metas')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('meta_group_meta', function(Blueprint $table)
		{
			$table->dropForeign('meta_group_meta_meta_group_id_foreign');
			$table->dropForeign('meta_group_meta_meta_id_foreign');
		});
	}

}
