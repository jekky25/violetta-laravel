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
		if (!Schema::hasTable('users_admin')) {
			Schema::create('users_admin', function (Blueprint $table) {
				$table->integer('user_id');
				$table->string('user_login');
				$table->string('user_password');
				$table->string('user_hash');
				$table->string('user_mail');
				$table->integer('user_lastvisit')->default(0);
				$table->char('user_ip', 8);
			});
		}

		Schema::table('users_admin', function (Blueprint $table) {
            $table->primary('user_id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('users_admin', function (Blueprint $table) {
			$table->dropPrimary('user_id');
		});
		Schema::dropIfExists('users_admin');
	}
};