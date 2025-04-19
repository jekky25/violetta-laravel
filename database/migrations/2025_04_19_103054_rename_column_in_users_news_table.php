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
		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_sex', 'sex');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_mail', 'email');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_hash', 'hash');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_password', 'password');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_login', 'login');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('sex', 'user_sex');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('email', 'user_mail');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('hash', 'user_hash');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('password', 'user_password');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('login', 'user_login');
		});
    }
};
