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
		if (!Schema::hasTable('names')) {
			Schema::create('names', function (Blueprint $table) {
				$table->integer('id');
				$table->integer('name_id');
				$table->string('name');
				$table->text('description');
				$table->tinyInteger('first_bukva');
				$table->tinyInteger('gender');
			});
		}

		Schema::table('names', function (Blueprint $table) {
            $table->primary('id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('names', function (Blueprint $table) {
			$table->dropPrimary('id');
		});
		Schema::dropIfExists('names');
	}
};
