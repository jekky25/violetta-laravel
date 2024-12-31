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
		if (!Schema::hasTable('error_mess')) {
			Schema::create('error_mess', function (Blueprint $table) {
				$table->integer('error_mess_id');
				$table->string('error_mess_text');
				$table->dateTime('error_mess_date');
				$table->string('error_mess_find');
			});
		}

		Schema::table('error_mess', function (Blueprint $table) {
            $table->primary('error_mess_id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('error_mess', function (Blueprint $table) {
			$table->dropPrimary('error_mess_id');
		});
		Schema::dropIfExists('error_mess');
	}
};