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
		if (!Schema::hasTable('smiles')) {
			Schema::create('smiles', function (Blueprint $table) {
				$table->integer('smile_id');
				$table->string('smile_code');
				$table->string('smile_img');
				$table->integer('smile_sort')->default(100);
			});
		}

		Schema::table('smiles', function (Blueprint $table) {
            $table->primary('smile_id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('smiles', function (Blueprint $table) {
			$table->dropPrimary('smile_id');
		});
		Schema::dropIfExists('smiles');
	}
};