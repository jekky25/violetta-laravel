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
        if (!Schema::hasTable('body')) {
			Schema::create('body', function (Blueprint $table) {
				$table->integer('id');
				$table->string('name');
			});
		}

        Schema::table('body', function (Blueprint $table) {
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('body', function (Blueprint $table) {
			$table->dropPrimary('id');
		});

        Schema::dropIfExists('body');
    }
};
