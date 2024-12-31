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
		if (!Schema::hasTable('counter_day')) {
			Schema::create('counter_day', function (Blueprint $table) {
				$table->integer('id');
				$table->integer('count');
				$table->date('day');
				$table->string('host');
			});
		}

		Schema::table('counter_day', function (Blueprint $table) {
            $table->primary('id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('counter_day', function (Blueprint $table) {
			$table->dropPrimary('id');
		});
		Schema::dropIfExists('counter_day');
	}
};