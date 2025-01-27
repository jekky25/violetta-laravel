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
		Schema::table('dnevniki', function (Blueprint $table) {
			$table->renameColumn('dnevniki_time', 'create_time');
		});
		Schema::table('dnevniki', function (Blueprint $table) {
			$table->renameColumn('dnevniki_picture', 'picture');
		});
		Schema::table('dnevniki', function (Blueprint $table) {
			$table->renameColumn('dnevniki_text', 'description');
		});
		Schema::table('dnevniki', function (Blueprint $table) {
			$table->renameColumn('dnevniki_title', 'title');
		});
		Schema::table('dnevniki', function (Blueprint $table) {
			$table->renameColumn('dnevniki_user_id', 'user_id');
		});
		Schema::table('dnevniki', function (Blueprint $table) {
			$table->renameColumn('dnevniki_id', 'id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('dnevniki', function (Blueprint $table) {
			$table->renameColumn('create_time', 'dnevniki_time');
		});
		Schema::table('dnevniki', function (Blueprint $table) {
			$table->renameColumn('picture', 'dnevniki_picture');
		});
		Schema::table('dnevniki', function (Blueprint $table) {
			$table->renameColumn('description', 'dnevniki_text');
		});
		Schema::table('dnevniki', function (Blueprint $table) {
			$table->renameColumn('title', 'dnevniki_title');
		});
		Schema::table('dnevniki', function (Blueprint $table) {
			$table->renameColumn('user_id', 'dnevniki_user_id');
		});
		Schema::table('dnevniki', function (Blueprint $table) {
			$table->renameColumn('id', 'dnevniki_id');
		});
	}
};
