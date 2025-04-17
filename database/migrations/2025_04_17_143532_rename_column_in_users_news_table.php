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
			$table->renameColumn('user_children', 'children');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_sem_polozh', 'family_status');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_eyes', 'eyes');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_hair_type', 'hair_type');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_hair_color', 'hair_color');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('children', 'user_children');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('family_status', 'user_sem_polozh');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('eyes', 'user_eyes');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('hair_type', 'user_hair_type');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('hair_color', 'user_hair_color');
		});
    }
};
