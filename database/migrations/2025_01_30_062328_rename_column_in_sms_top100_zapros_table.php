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
		Schema::table('sms_top100_zapros', function (Blueprint $table) {
			$table->renameColumn('sms_kod', 'code');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('sms_top100_zapros', function (Blueprint $table) {
			$table->renameColumn('code', 'sms_kod');
		});
	}
};
