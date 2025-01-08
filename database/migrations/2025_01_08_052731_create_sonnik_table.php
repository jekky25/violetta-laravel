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
		if (!Schema::hasTable('sonnik')) {
			Schema::create('sonnik', function (Blueprint $table) {
				$table->integer('id');
				$table->integer('sonnik_id')->nullable()->default(28);
				$table->string('name');
				$table->text('description');
				$table->char('first_bukva')->nullable()->default('T');
			});
		}

		Schema::table('sonnik', function (Blueprint $table) {
            $table->primary('id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('sonnik', function (Blueprint $table) {
			$table->dropPrimary('id');
		});
		Schema::dropIfExists('sonnik');
	}
};