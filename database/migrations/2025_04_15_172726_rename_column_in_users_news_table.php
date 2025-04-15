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
			$table->renameColumn('user_partner_age_min', 'partner_age_min');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_partner_age_max', 'partner_age_max');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_partner_height_min', 'partner_height_min');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_partner_height_max', 'partner_height_max');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_partner_weight_min', 'partner_weight_min');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('partner_age_min', 'user_partner_age_min');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('partner_age_max', 'user_partner_age_max');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('partner_height_min', 'user_partner_height_min');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('partner_height_max', 'user_partner_height_max');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('partner_weight_min', 'user_partner_weight_min');
		});
	}
};
