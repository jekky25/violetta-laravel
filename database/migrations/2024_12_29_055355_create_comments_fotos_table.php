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
				$table->integer('comments_id')->autoIncrement();
				$table->integer('foto_id');
				$table->integer('user_id');
				$table->integer('time');
				$table->string('comments_description');
			});
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('comments_fotos');
	}
};
