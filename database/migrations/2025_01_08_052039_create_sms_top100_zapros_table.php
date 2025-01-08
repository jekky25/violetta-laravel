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
		if (!Schema::hasTable('sms_top100_zapros')) {
			Schema::create('sms_top100_zapros', function (Blueprint $table) {
				$table->string('pref');
				$table->string('sms_kod');
				$table->string('tid');
				$table->string('cn');
				$table->string('op');
				$table->string('phone');
				$table->string('sn')->nullable();
				$table->integer('user_id')->default(0);
				$table->integer('time')->default(0);
			});
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('sms_top100_zapros');
	}
};