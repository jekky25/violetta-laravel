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
			$table->renameColumn('user_make_date', 'make_date');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_make_date_t', 'make_date_t');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_refresh_date', 'refresh_date');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_refresh_date_t', 'refresh_date_t');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_sex_orient', 'sex_orient');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('make_date', 'user_make_date');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('make_date_t', 'user_make_date_t');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('refresh_date', 'user_refresh_date');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('refresh_date_t', 'user_refresh_date_t');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('sex_orient', 'user_sex_orient');
		});
	}
};
