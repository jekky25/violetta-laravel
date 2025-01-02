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
		if (!Schema::hasTable('modules')) {
			Schema::create('modules', function (Blueprint $table) {
				$table->integer('id');
				$table->string('name');
				$table->string('title');
				$table->tinyInteger('active');
			});
		}

		Schema::table('modules', function (Blueprint $table) {
            $table->primary('id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('modules', function (Blueprint $table) {
			$table->dropPrimary('id');
		});
		Schema::dropIfExists('modules');
	}
};
