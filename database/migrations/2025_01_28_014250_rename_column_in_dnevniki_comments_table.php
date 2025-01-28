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
		Schema::table('dnevniki_comments', function (Blueprint $table) {
			$table->renameColumn('comment_time', 'create_time');
		});
		Schema::table('dnevniki_comments', function (Blueprint $table) {
			$table->renameColumn('comment_picture', 'picture');
		});
		Schema::table('dnevniki_comments', function (Blueprint $table) {
			$table->renameColumn('comment_text', 'description');
		});
		Schema::table('dnevniki_comments', function (Blueprint $table) {
			$table->renameColumn('comment_title', 'title');
		});
		Schema::table('dnevniki_comments', function (Blueprint $table) {
			$table->renameColumn('comment_dnevnik_user_id', 'user_id');
		});
		Schema::table('dnevniki_comments', function (Blueprint $table) {
			$table->renameColumn('comment_dnevnik_id', 'diary_id');
		});
		Schema::table('dnevniki_comments', function (Blueprint $table) {
			$table->renameColumn('comment_id', 'id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('dnevniki_comments', function (Blueprint $table) {
			$table->renameColumn('create_time', 'comment_time');
		});
		Schema::table('dnevniki_comments', function (Blueprint $table) {
			$table->renameColumn('picture', 'comment_picture');
		});
		Schema::table('dnevniki_comments', function (Blueprint $table) {
			$table->renameColumn('description', 'comment_text');
		});
		Schema::table('dnevniki_comments', function (Blueprint $table) {
			$table->renameColumn('title', 'comment_title');
		});
		Schema::table('dnevniki_comments', function (Blueprint $table) {
			$table->renameColumn('user_id', 'comment_dnevnik_user_id');
		});
		Schema::table('dnevniki_comments', function (Blueprint $table) {
			$table->renameColumn('diary_id', 'comment_dnevnik_id');
		});
		Schema::table('dnevniki_comments', function (Blueprint $table) {
			$table->renameColumn('id', 'comment_id');
		});
    }
};
