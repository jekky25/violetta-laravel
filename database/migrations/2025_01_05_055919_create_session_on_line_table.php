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
		if (!Schema::hasTable('session_on_line')) {
			Schema::create('session_on_line', function (Blueprint $table) {
				$table->integer('session_id');
				$table->integer('session_time');
				$table->tinyInteger('user_reg_is')->default(0);
			});
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('session_on_line');
	}
};