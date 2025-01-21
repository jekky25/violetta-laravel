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
		if (!Schema::hasTable('dnevniki')) {
			Schema::create('dnevniki', function (Blueprint $table) {
				$table->integer('dnevniki_id')->autoIncrement();
				$table->integer('dnevniki_user_id');
				$table->string('dnevniki_title');
				$table->text('dnevniki_text');
				$table->string('dnevniki_picture');
				$table->integer('dnevniki_time');
			});
		}

		Schema::table('dnevniki', function (Blueprint $table) {
            $table->primary('dnevniki_id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('dnevniki', function (Blueprint $table) {
			$table->dropPrimary('dnevniki_id');
		});
		Schema::dropIfExists('dnevniki');
	}
};