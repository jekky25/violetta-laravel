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
		if (!Schema::hasTable('comments_screen_servers')) {
			Schema::create('comments_screen_servers', function (Blueprint $table) {
				$table->integer('id');
				$table->integer('scr_id');
				$table->integer('user_id');
				$table->integer('time');
				$table->string('name');
				$table->string('email');
				$table->text('description');
			});
		}

		Schema::table('comments_screen_servers', function (Blueprint $table) {
            $table->primary('id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('comments_screen_servers', function (Blueprint $table) {
			$table->dropPrimary('id');
		});
		Schema::dropIfExists('comments_screen_servers');
	}
};