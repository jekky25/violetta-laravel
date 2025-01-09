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
		if (!Schema::hasTable('zapros_err_login')) {
			Schema::create('zapros_err_login', function (Blueprint $table) {
				$table->integer('id');
				$table->string('login');
				$table->string('password');
				$table->string('md5');
				$table->text('sql_zapr')->nullable();
				$table->dateTime('time');
			});
		}

		Schema::table('zapros_err_login', function (Blueprint $table) {
            $table->primary('id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('zapros_err_login', function (Blueprint $table) {
			$table->dropPrimary('id');
		});
		Schema::dropIfExists('zapros_err_login');
	}
};