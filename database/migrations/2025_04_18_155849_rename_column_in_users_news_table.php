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
			$table->renameColumn('user_body', 'body');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_weight', 'weight');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_height', 'height');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_city', 'city_id');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_region', 'region_id');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('body', 'user_body');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('weight', 'user_weight');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('height', 'user_height');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('city_id', 'user_city');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('region_id', 'user_region');
		});
    }
};
