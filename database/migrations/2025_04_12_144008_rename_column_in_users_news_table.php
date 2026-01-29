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
			$table->renameColumn('user_url', 'url');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_phone', 'phone');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_icq', 'icq');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_partner_description', 'partner_description');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_interests', 'interests');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('url', 'user_url');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('phone', 'user_phone');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('icq', 'user_icq');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('partner_description', 'user_partner_description');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('interests', 'user_interests');
		});
    }
};
