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
		if (!Schema::hasTable('children')) {
			Schema::create('children', function (Blueprint $table) {
				$table->integer('id');
				$table->string('name');
			});
		}
		Schema::table('children', function (Blueprint $table) {
            $table->primary('id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('children', function (Blueprint $table) {
			$table->dropPrimary('id');
		});
		Schema::dropIfExists('children');
	}
};
