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
		Schema::table('goroskop', function (Blueprint $table) {
			$table->renameColumn('gor_text', 'description');
		});

		Schema::table('goroskop', function (Blueprint $table) {
			$table->renameColumn('gor_dopoln', 'addition');
		});

		Schema::table('goroskop', function (Blueprint $table) {
			$table->renameColumn('gor_type', 'type');
		});

		Schema::table('goroskop', function (Blueprint $table) {
			$table->renameColumn('gor_name', 'name');
		});

		Schema::table('goroskop', function (Blueprint $table) {
			$table->renameColumn('gor_id', 'id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('goroskop', function (Blueprint $table) {
			$table->renameColumn('description', 'gor_text');
		});

		Schema::table('goroskop', function (Blueprint $table) {
			$table->renameColumn('addition', 'gor_dopoln');
		});

		Schema::table('goroskop', function (Blueprint $table) {
			$table->renameColumn('type', 'gor_type');
		});

		Schema::table('goroskop', function (Blueprint $table) {
			$table->renameColumn('name', 'gor_name');
		});

		Schema::table('goroskop', function (Blueprint $table) {
			$table->renameColumn('id', 'gor_id');
		});
	}
};
