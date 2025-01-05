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
		if (!Schema::hasTable('sessions_keys')) {
			Schema::create('sessions_keys', function (Blueprint $table) {
				$table->integer('key_id')->default(0);
				$table->integer('user_id')->default(0);
				$table->string('last_ip', 8);
				$table->integer('last_login')->default(0);
			});
		}

		Schema::table('sessions_keys', function (Blueprint $table) {
            $table->primary('key_id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('sessions_keys', function (Blueprint $table) {
			$table->dropPrimary('key_id');
		});
		Schema::dropIfExists('sessions_keys');
	}
};