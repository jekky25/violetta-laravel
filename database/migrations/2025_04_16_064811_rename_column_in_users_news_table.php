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
			$table->renameColumn('user_education', 'education');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_smoke', 'smoke');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_spirt', 'alcohol');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_help_money', 'help_money');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_speak_lang', 'speak_lang');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('education', 'user_education');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('smoke', 'user_smoke');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('alcohol', 'user_spirt');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('help_money', 'user_help_money');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('speak_lang', 'user_speak_lang');
		});
    }
};
