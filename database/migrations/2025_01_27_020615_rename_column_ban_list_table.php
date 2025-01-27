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
			$table->renameColumn('ban_ip', 'ip');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('ban_list', function (Blueprint $table) {
			$table->renameColumn('ip', 'ban_ip');
		});
	}
};
