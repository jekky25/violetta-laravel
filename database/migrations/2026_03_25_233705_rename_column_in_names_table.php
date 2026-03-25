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
        Schema::table('names', function (Blueprint $table) {
            $table->char('first_bukva', 1)->change();
            $table->char('gender', 1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('names', function (Blueprint $table) {
            $table->tinyInteger('first_bukva')->change();
            $table->tinyInteger('gender')->change();
        });
    }
};
