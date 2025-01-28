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
		Schema::table('fotos', function (Blueprint $table) {
			$table->renameColumn('fotos_t', 'create_time');
		});

		Schema::table('fotos', function (Blueprint $table) {
			$table->renameColumn('fotos_portret', 'main_picture');
		});

		Schema::table('fotos', function (Blueprint $table) {
			$table->renameColumn('fotos_id', 'id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('fotos', function (Blueprint $table) {
			$table->renameColumn('create_time', 'fotos_t');
		});

		Schema::table('fotos', function (Blueprint $table) {
			$table->renameColumn('main_picture', 'fotos_portret');
		});

		Schema::table('fotos', function (Blueprint $table) {
			$table->renameColumn('id', 'fotos_id');
		});
	}
};
