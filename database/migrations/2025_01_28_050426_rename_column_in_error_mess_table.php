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
		Schema::table('error_mess', function (Blueprint $table) {
			$table->renameColumn('error_mess_find', 'find');
		});

		Schema::table('error_mess', function (Blueprint $table) {
			$table->renameColumn('error_mess_date', 'date');
		});

		Schema::table('error_mess', function (Blueprint $table) {
			$table->renameColumn('error_mess_text', 'description');
		});

		Schema::table('error_mess', function (Blueprint $table) {
			$table->renameColumn('error_mess_id', 'id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('error_mess', function (Blueprint $table) {
			$table->renameColumn('find', 'error_mess_find');
		});

		Schema::table('error_mess', function (Blueprint $table) {
			$table->renameColumn('date', 'error_mess_date');
		});

		Schema::table('error_mess', function (Blueprint $table) {
			$table->renameColumn('description', 'error_mess_text');
		});

		Schema::table('error_mess', function (Blueprint $table) {
			$table->renameColumn('id', 'error_mess_id');
		});
	}
};