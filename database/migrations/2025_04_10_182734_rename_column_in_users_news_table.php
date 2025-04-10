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
			$table->renameColumn('user_confirm_email', 'confirm_email');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_submit_code', 'submit_code');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_odobreno', 'approved');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_top100', 'top100');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_ip', 'ip');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('confirm_email', 'user_confirm_email');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('submit_code', 'user_submit_code');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('approved', 'user_odobreno');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('top100', 'user_top100');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('ip', 'user_ip');
		});
	}
};
