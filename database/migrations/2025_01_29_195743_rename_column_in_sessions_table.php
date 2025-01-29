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
		Schema::table('sessions', function (Blueprint $table) {
			$table->renameColumn('session_logged_in', 'logged_in');
		});

		Schema::table('sessions', function (Blueprint $table) {
			$table->renameColumn('session_ip', 'ip');
		});

		Schema::table('sessions', function (Blueprint $table) {
			$table->renameColumn('session_time', 'create_time');
		});

		Schema::table('sessions', function (Blueprint $table) {
			$table->renameColumn('session_start', 'start');
		});

		Schema::table('sessions', function (Blueprint $table) {
			$table->renameColumn('session_user_id', 'user_id');
		});

		Schema::table('sessions', function (Blueprint $table) {
			$table->renameColumn('session_id', 'id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('sessions', function (Blueprint $table) {
			$table->renameColumn('logged_in', 'session_logged_in');
		});

		Schema::table('sessions', function (Blueprint $table) {
			$table->renameColumn('ip', 'session_ip');
		});

		Schema::table('sessions', function (Blueprint $table) {
			$table->renameColumn('create_time', 'session_time');
		});

		Schema::table('sessions', function (Blueprint $table) {
			$table->renameColumn('start', 'session_start');
		});

		Schema::table('sessions', function (Blueprint $table) {
			$table->renameColumn('user_id', 'session_user_id');
		});

		Schema::table('sessions', function (Blueprint $table) {
			$table->renameColumn('id', 'session_id');
		});
	}
};
