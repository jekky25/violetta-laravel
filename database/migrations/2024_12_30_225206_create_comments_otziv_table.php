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
		if (!Schema::hasTable('comments_otziv')) {
			Schema::create('comments_otziv', function (Blueprint $table) {
				$table->integer('comments_id');
				$table->integer('user_id');
				$table->integer('time');
				$table->text('comments_description');
			});
		}

		Schema::table('comments_otziv', function (Blueprint $table) {
            $table->primary('comments_id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('comments_otziv', function (Blueprint $table) {
			$table->dropPrimary('comments_id');
		});
		Schema::dropIfExists('comments_otziv');
	}
};