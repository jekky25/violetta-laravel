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
		if (!Schema::hasTable('sessions')) {
			Schema::create('sessions', function (Blueprint $table) {
				$table->integer('session_id');
				$table->integer('session_user_id');
				$table->integer('session_start');
				$table->integer('session_time');
				$table->char('session_ip', 8);
				$table->tinyInteger('session_logged_in');
			});
		}

		Schema::table('sessions', function (Blueprint $table) {
            $table->primary('session_id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('sessions', function (Blueprint $table) {
			$table->dropPrimary('session_id');
		});
		Schema::dropIfExists('sessions');
	}
};