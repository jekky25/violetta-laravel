<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		if (!Schema::hasTable('dnevniki_comments')) {
			Schema::create('dnevniki_comments', function (Blueprint $table) {
				$table->integer('comment_id')->autoIncrement();
				$table->integer('comment_dnevnik_id');
				$table->integer('comment_dnevnik_user_id');
				$table->string('comment_title');
				$table->text('comment_text');
				$table->string('comment_picture');
				$table->integer('comment_time');
			});
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('dnevniki_comments');
	}
};