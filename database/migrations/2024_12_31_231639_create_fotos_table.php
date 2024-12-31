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
		if (!Schema::hasTable('fotos')) {
			Schema::create('fotos', function (Blueprint $table) {
				$table->integer('fotos_id');
				$table->integer('user_id');
				$table->tinyInteger('fotos_portret');
				$table->integer('fotos_t');
			});
		}

		Schema::table('fotos', function (Blueprint $table) {
            $table->primary('fotos_id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('fotos', function (Blueprint $table) {
			$table->dropPrimary('fotos_id');
		});
		Schema::dropIfExists('fotos');
	}
};