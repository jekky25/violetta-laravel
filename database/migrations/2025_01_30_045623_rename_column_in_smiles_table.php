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
		Schema::table('smiles', function (Blueprint $table) {
			$table->renameColumn('smile_sort', 'sort');
		});

		Schema::table('smiles', function (Blueprint $table) {
			$table->renameColumn('smile_img', 'img');
		});

		Schema::table('smiles', function (Blueprint $table) {
			$table->renameColumn('smile_code', 'code');
		});

		Schema::table('smiles', function (Blueprint $table) {
			$table->renameColumn('smile_id', 'id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('smiles', function (Blueprint $table) {
			$table->renameColumn('sort', 'smile_sort');
		});

		Schema::table('smiles', function (Blueprint $table) {
			$table->renameColumn('img', 'smile_img');
		});

		Schema::table('smiles', function (Blueprint $table) {
			$table->renameColumn('code', 'smile_code');
		});

		Schema::table('smiles', function (Blueprint $table) {
			$table->renameColumn('id', 'smile_id');
		});
	}
};
