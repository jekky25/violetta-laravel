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
		if (!Schema::hasTable('target_meet')) {
			Schema::create('target_meet', function (Blueprint $table) {
				$table->integer('id');
				$table->string('name');
			});
		}

		Schema::table('target_meet', function (Blueprint $table) {
            $table->primary('id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('target_meet', function (Blueprint $table) {
			$table->dropPrimary('id');
		});
		Schema::dropIfExists('target_meet');
	}
};
