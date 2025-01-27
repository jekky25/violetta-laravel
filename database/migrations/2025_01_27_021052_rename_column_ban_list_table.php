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
		Schema::table('ban_list', function (Blueprint $table) {
			$table->renameColumn('ban_id', 'id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('ban_list', function (Blueprint $table) {
			$table->renameColumn('id', 'ban_id');
		});
	}
};
