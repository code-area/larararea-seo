<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeoMetaContentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seo_meta_contents', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('parent_id')->unsigned()->nullable()->index('seo_meta_contents_parent_id_foreign');
			$table->integer('seo_id')->unsigned()->nullable()->index('seo_meta_contents_seo_id_foreign');
			$table->integer('meta_id')->unsigned()->nullable()->index('seo_meta_contents_meta_id_foreign');
			$table->integer('meta_group_id')->unsigned()->nullable()->index('seo_meta_contents_meta_group_id_foreign');
			$table->smallInteger('is_active')->nullable();
			$table->string('lang', 191)->nullable();
			$table->string('content', 191)->nullable();
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
		Schema::drop('seo_meta_contents');
	}

}
