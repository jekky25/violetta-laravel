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
		if (!Schema::hasTable('country')) {
			Schema::create('country', function (Blueprint $table) {
				$table->integer('id')->autoIncrement();
				$table->string('name');
			});
		}

		Schema::table('country', function (Blueprint $table) {
            $table->primary('id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('country', function (Blueprint $table) {
			$table->dropPrimary('id');
		});
		Schema::dropIfExists('country');
	}
};