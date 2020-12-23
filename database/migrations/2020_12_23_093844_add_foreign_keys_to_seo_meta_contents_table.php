<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSeoMetaContentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('seo_meta_contents', function(Blueprint $table)
		{
			$table->foreign('meta_group_id')->references('id')->on('meta_groups')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('meta_id')->references('id')->on('metas')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('parent_id')->references('id')->on('seo_meta_contents')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('seo_id')->references('id')->on('seo')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('seo_meta_contents', function(Blueprint $table)
		{
			$table->dropForeign('seo_meta_contents_meta_group_id_foreign');
			$table->dropForeign('seo_meta_contents_meta_id_foreign');
			$table->dropForeign('seo_meta_contents_parent_id_foreign');
			$table->dropForeign('seo_meta_contents_seo_id_foreign');
		});
	}

}
