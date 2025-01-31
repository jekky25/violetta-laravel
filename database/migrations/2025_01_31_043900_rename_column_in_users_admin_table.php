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
		Schema::table('users_admin', function (Blueprint $table) {
			$table->renameColumn('user_ip', 'ip');
		});

		Schema::table('users_admin', function (Blueprint $table) {
			$table->renameColumn('user_lastvisit', 'lastvisit');
		});

		Schema::table('users_admin', function (Blueprint $table) {
			$table->renameColumn('user_mail', 'email');
		});

		Schema::table('users_admin', function (Blueprint $table) {
			$table->renameColumn('user_hash', 'hash');
		});

		Schema::table('users_admin', function (Blueprint $table) {
			$table->renameColumn('user_password', 'password');
		});

		Schema::table('users_admin', function (Blueprint $table) {
			$table->renameColumn('user_login', 'login');
		});

		Schema::table('users_admin', function (Blueprint $table) {
			$table->renameColumn('user_id', 'id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('users_admin', function (Blueprint $table) {
			$table->renameColumn('ip', 'user_ip');
		});

		Schema::table('users_admin', function (Blueprint $table) {
			$table->renameColumn('lastvisit', 'user_lastvisit');
		});

		Schema::table('users_admin', function (Blueprint $table) {
			$table->renameColumn('email', 'user_mail');
		});

		Schema::table('users_admin', function (Blueprint $table) {
			$table->renameColumn('hash', 'user_hash');
		});

		Schema::table('users_admin', function (Blueprint $table) {
			$table->renameColumn('password', 'user_password');
		});

		Schema::table('users_admin', function (Blueprint $table) {
			$table->renameColumn('login',  'user_login');
		});

		Schema::table('users_admin', function (Blueprint $table) {
			$table->renameColumn('id', 'user_id');
		});
	}
};
