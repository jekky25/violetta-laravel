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
		if (!Schema::hasTable('goroskop')) {
			Schema::create('goroskop', function (Blueprint $table) {
				$table->integer('gor_id');
				$table->string('gor_name');
				$table->tinyInteger('gor_type');
				$table->integer('gor_dopoln');
				$table->text('gor_text');
			});
		}

		Schema::table('goroskop', function (Blueprint $table) {
            $table->primary('gor_id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('goroskop', function (Blueprint $table) {
			$table->dropPrimary('gor_id');
		});
		Schema::dropIfExists('goroskop');
	}
};