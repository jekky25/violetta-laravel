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
		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_target_meet', 'targets');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_description', 'description');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_new_message', 'new_messages');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_lastvisit_views', 'lastvisit_views');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_lastvisit', 'lastvisit');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('targets', 'user_target_meet');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('description', 'user_description');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('new_messages', 'user_new_message');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('lastvisit_views', 'user_lastvisit_views');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('lastvisit', 'user_lastvisit');
		});
	}
};
