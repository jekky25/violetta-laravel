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
		if (!Schema::hasTable('eyes')) {
			Schema::create('eyes', function (Blueprint $table) {
				$table->integer('id');
				$table->string('name');
			});
		}

		Schema::table('eyes', function (Blueprint $table) {
            $table->primary('id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('eyes', function (Blueprint $table) {
			$table->dropPrimary('id');
		});
		Schema::dropIfExists('eyes');
	}
};
