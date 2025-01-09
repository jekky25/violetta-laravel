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
		if (!Schema::hasTable('user_messages')) {
			Schema::create('user_messages', function (Blueprint $table) {
				$table->integer('message_id');
				$table->integer('user_otprav')->default(0);
				$table->integer('user_poluchil')->default(0);
				$table->tinyInteger('user_otprav_del')->default(0);
				$table->tinyInteger('user_poluchil_del')->default(0);
				$table->integer('time')->default(0);
				$table->tinyInteger('mess_new')->default(1);
				$table->text('privmess_text');
			});
		}

		Schema::table('user_messages', function (Blueprint $table) {
            $table->primary('message_id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('user_messages', function (Blueprint $table) {
			$table->dropPrimary('message_id');
		});
		Schema::dropIfExists('user_messages');
	}
};