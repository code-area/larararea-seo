<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seo', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('parent_id')->unsigned()->nullable()->index('seo_parent_id_foreign')->comment('parent id used for many languages if no records in lang that case must be show parent_id metadata');
			$table->smallInteger('is_active')->nullable();
			$table->smallInteger('is_minify')->nullable();
			$table->string('lang', 191)->nullable()->comment('related to parent_id if parent_id is null that case language also must be null');
			$table->string('route_name', 191)->nullable();
			$table->string('headline', 191)->nullable()->comment('this is used for humans not for seo');
			$table->string('uri', 191)->nullable()->comment('route uri');
			$table->string('title', 191)->nullable();
			$table->string('robots', 191)->nullable();
			$table->string('description', 191)->nullable();
			$table->string('keywords', 191)->nullable();
			$table->text('html', 65535)->nullable();
			$table->text('meta_json')->nullable();
			$table->text('tags')->nullable();
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
		Schema::drop('seo');
	}

}
