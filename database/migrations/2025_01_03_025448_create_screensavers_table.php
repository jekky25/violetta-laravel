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
		if (!Schema::hasTable('screensavers')) {
			Schema::create('screensavers', function (Blueprint $table) {
				$table->integer('id')->autoIncrement();
				$table->date('date');
				$table->string('name');
				$table->string('path_scr');
				$table->string('path_rar');
				$table->string('path_jpg');
				$table->integer('screen_sub');
				$table->integer('size_scr');
				$table->integer('size_rar');
				$table->integer('zakachka');
			});
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('screensavers');
	}
};