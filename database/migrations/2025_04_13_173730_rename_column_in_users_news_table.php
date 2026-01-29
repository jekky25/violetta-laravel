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
			$table->renameColumn('user_partner_body', 'partner_body');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_partner_city', 'partner_city');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_partner_region', 'partner_region');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_partner_country', 'partner_country');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('user_partner_weight_max', 'partner_weight_max');
		});
	}

    /**
     * Reverse the migrations.
     */
	public function down(): void
	{
		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('partner_body', 'user_partner_body');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('partner_city', 'user_partner_city');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('partner_region', 'user_partner_region');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('partner_country', 'user_partner_country');
		});

		Schema::table('users_news', function (Blueprint $table) {
			$table->renameColumn('partner_weight_max', 'user_partner_weight_max');
		});
	}
};
