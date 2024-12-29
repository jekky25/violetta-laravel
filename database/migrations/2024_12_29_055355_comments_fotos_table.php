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
		if (!Schema::hasTable('comments_fotos')) {
			Schema::create('comments_fotos', function (Blueprint $table) {
				$table->integer('foto_id');
				$table->integer('user_id');
				$table->integer('time');
				$table->string('comments_description');
			});
		}

		Schema::table('comments_fotos', function (Blueprint $table) {
            $table->primary('foto_id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('comments_fotos', function (Blueprint $table) {
			$table->dropPrimary('foto_id');
		});
		Schema::dropIfExists('comments_fotos');
	}
};
