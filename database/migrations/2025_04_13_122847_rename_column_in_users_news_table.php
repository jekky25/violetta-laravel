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
			$table->renameColumn('user_session_time', 'session_time');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_partner_spirt', 'partner_alcohol');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_partner_smoke', 'partner_smoke');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_partner_education', 'partner_education');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_partner_speak_lang', 'partner_languages');
		});
	}

    /**
     * Reverse the migrations.
     */
	public function down(): void
	{
		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('session_time', 'user_session_time');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('partner_alcohol', 'user_partner_spirt');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('partner_smoke', 'user_partner_smoke');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('partner_education', 'user_partner_education');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('partner_languages', 'user_partner_speak_lang');
		});
	}
};
