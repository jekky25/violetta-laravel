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
		if (!Schema::hasTable('spirt')) {
			Schema::create('spirt', function (Blueprint $table) {
				$table->integer('id');
				$table->string('name');
			});
		}

		Schema::table('spirt', function (Blueprint $table) {
            $table->primary('id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('spirt', function (Blueprint $table) {
			$table->dropPrimary('id');
		});
		Schema::dropIfExists('spirt');
	}
};
