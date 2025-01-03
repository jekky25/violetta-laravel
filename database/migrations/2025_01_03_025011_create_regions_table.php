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
		if (!Schema::hasTable('regions')) {
			Schema::create('regions', function (Blueprint $table) {
				$table->integer('id');
				$table->string('name');
				$table->integer('country_id');
			});
		}

		Schema::table('regions', function (Blueprint $table) {
            $table->primary('id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('regions', function (Blueprint $table) {
			$table->dropPrimary('id');
		});
		Schema::dropIfExists('regions');
	}
};
