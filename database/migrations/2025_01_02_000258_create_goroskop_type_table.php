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
		if (!Schema::hasTable('goroskop_type')) {
			Schema::create('goroskop_type', function (Blueprint $table) {
				$table->integer('gor_type_id');
				$table->string('gor_type_name');
			});
		}

		Schema::table('goroskop_type', function (Blueprint $table) {
            $table->primary('gor_type_id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('goroskop_type', function (Blueprint $table) {
			$table->dropPrimary('gor_type_id');
		});
		Schema::dropIfExists('goroskop_type');
	}
};
