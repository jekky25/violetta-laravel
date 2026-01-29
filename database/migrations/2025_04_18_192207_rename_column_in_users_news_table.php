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
			$table->renameColumn('user_country', 'country_id');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_birth_date', 'birth_date');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_name', 'name');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_reiting', 'rating');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_fotos', 'photos_count');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('country_id', 'user_country');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('birth_date', 'user_birth_date');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('name', 'user_name');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('rating', 'user_reiting');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('photos_count', 'user_fotos');
		});
    }
};
