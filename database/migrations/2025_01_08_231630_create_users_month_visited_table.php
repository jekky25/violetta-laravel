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
		if (!Schema::hasTable('users_month_visited')) {
			Schema::create('users_month_visited', function (Blueprint $table) {
				$table->integer('id');
				$table->integer('user_id')->default(0);
				$table->integer('user_m_time')->default(0);
			});
		}

		Schema::table('users_month_visited', function (Blueprint $table) {
            $table->primary('id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('users_month_visited', function (Blueprint $table) {
			$table->dropPrimary('id');
		});
		Schema::dropIfExists('users_month_visited');
	}
};