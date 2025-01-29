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
		Schema::table('session_on_line', function (Blueprint $table) {
			$table->renameColumn('session_time', 'create_time');
		});

		Schema::table('session_on_line', function (Blueprint $table) {
			$table->renameColumn('session_id', 'id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('session_on_line', function (Blueprint $table) {
			$table->renameColumn('create_time', 'session_time');
		});

		Schema::table('session_on_line', function (Blueprint $table) {
			$table->renameColumn('id', 'session_id');
		});
	}
};
