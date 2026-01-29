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
		Schema::table('user_messages', function (Blueprint $table) {
			$table->renameColumn('user_poluchil', 'received_user_id');
		});

		Schema::table('user_messages', function (Blueprint $table) {
			$table->renameColumn('user_otprav', 'sent_user_id');
		});

		Schema::table('user_messages', function (Blueprint $table) {
			$table->renameColumn('message_id', 'id');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		Schema::table('user_messages', function (Blueprint $table) {
			$table->renameColumn('received_user_id', 'user_poluchil');
		});

		Schema::table('user_messages', function (Blueprint $table) {
			$table->renameColumn('sent_user_id', 'user_otprav');
		});

		Schema::table('user_messages', function (Blueprint $table) {
			$table->renameColumn('id', 'message_id');
		});
    }
};
