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
		if (!Schema::hasTable('sms_top100')) {
			Schema::create('sms_top100', function (Blueprint $table) {
				$table->integer('sms_kod')->default(0);
				$table->integer('sms_kod_answer')->default(0);
				$table->integer('user_id')->default(0);
				$table->integer('time')->default(0);
			});
		}

		Schema::table('sms_top100', function (Blueprint $table) {
            $table->primary('sms_kod');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('sms_top100', function (Blueprint $table) {
			$table->dropPrimary('sms_kod');
		});
		Schema::dropIfExists('sms_top100');
	}
};
