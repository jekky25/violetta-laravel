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
		Schema::table('goroskop_type', function (Blueprint $table) {
			$table->renameColumn('gor_type_name', 'name');
		});

		Schema::table('goroskop_type', function (Blueprint $table) {
			$table->renameColumn('gor_type_id', 'id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('goroskop_type', function (Blueprint $table) {
			$table->renameColumn('name', 'gor_type_name');
		});

		Schema::table('goroskop_type', function (Blueprint $table) {
			$table->renameColumn('id', 'gor_type_id');
		});
	}
};
