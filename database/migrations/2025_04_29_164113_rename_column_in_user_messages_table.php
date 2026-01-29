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
			$table->renameColumn('privmess_text', 'description');
		});

		Schema::table('user_messages', function (Blueprint $table) {
			$table->renameColumn('mess_new', 'is_new');
		});

		Schema::table('user_messages', function (Blueprint $table) {
			$table->renameColumn('time', 'create_time');
		});

		Schema::table('user_messages', function (Blueprint $table) {
			$table->renameColumn('user_poluchil_del', 'received_is_deleted');
		});

		Schema::table('user_messages', function (Blueprint $table) {
			$table->renameColumn('user_otprav_del', 'sent_is_deleted');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		Schema::table('user_messages', function (Blueprint $table) {
			$table->renameColumn('description', 'privmess_text');
		});

		Schema::table('user_messages', function (Blueprint $table) {
			$table->renameColumn('is_new', 'mess_new');
		});

		Schema::table('user_messages', function (Blueprint $table) {
			$table->renameColumn('create_time', 'time');
		});

		Schema::table('user_messages', function (Blueprint $table) {
			$table->renameColumn('received_is_deleted', 'user_poluchil_del');
		});

		Schema::table('user_messages', function (Blueprint $table) {
			$table->renameColumn('sent_is_deleted', 'user_otprav_del');
		});
    }
};
