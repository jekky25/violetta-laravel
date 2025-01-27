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
		Schema::table('comments_otziv', function (Blueprint $table) {
			$table->renameColumn('comments_description', 'description');
		});
		Schema::table('comments_otziv', function (Blueprint $table) {
			$table->renameColumn('comments_id', 'id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('comments_otziv', function (Blueprint $table) {
			$table->renameColumn('description', 'comments_description');
		});
		Schema::table('comments_otziv', function (Blueprint $table) {
			$table->renameColumn('id', 'comments_id');
		});
	}
};
