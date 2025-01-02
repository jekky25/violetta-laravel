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
		if (!Schema::hasTable('ocenka_anket')) {
			Schema::create('ocenka_anket', function (Blueprint $table) {
				$table->integer('id');
				$table->integer('user_id');
				$table->integer('user_id_ocenka');
				$table->tinyInteger('ball');
				$table->integer('time');
			});
		}

		Schema::table('ocenka_anket', function (Blueprint $table) {
            $table->primary('id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('ocenka_anket', function (Blueprint $table) {
			$table->dropPrimary('id');
		});
		Schema::dropIfExists('ocenka_anket');
	}
};