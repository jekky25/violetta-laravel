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
		if (!Schema::hasTable('anket_visit')) {
			Schema::create('anket_visit', function (Blueprint $table) {
				$table->integer('id');
				$table->integer('ank_user_id')->default(0);
				$table->integer('user_id_prosm')->default(0);
				$table->integer('ank_time')->default(0);
			});
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		if (Schema::hasTable('anket_visit')) {
			Schema::dropIfExists('anket_visit');
		}
	}
};