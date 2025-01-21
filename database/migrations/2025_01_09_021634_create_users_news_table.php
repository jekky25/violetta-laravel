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
		if (!Schema::hasTable('users_news')) {
			Schema::create('users_news', function (Blueprint $table) {
				$table->integer('user_id')->autoIncrement();
				$table->tinyInteger('user_active')->default(0);
				$table->string('user_login');
				$table->string('user_password');
				$table->string('user_hash');
				$table->string('user_mail');
				$table->tinyInteger('user_sex')->default(1);
				$table->tinyInteger('user_fotos')->default(0);
				$table->integer('user_reiting')->default(0);
				$table->string('user_name');
				$table->date('user_birth_date')->default('0000-00-00');
				$table->integer('user_country')->default(0);
				$table->integer('user_region')->default(0);
				$table->integer('user_city')->default(0);
				$table->integer('user_height')->default(149);
				$table->integer('user_weight')->default(29);
				$table->tinyInteger('user_body')->default(0);
				$table->tinyInteger('user_hair_color')->default(0);
				$table->tinyInteger('user_hair_type')->default(0);
				$table->tinyInteger('user_eyes')->default(0);
				$table->tinyInteger('user_sem_polozh')->default(0);
				$table->tinyInteger('user_children')->default(0);
				$table->tinyInteger('user_education')->default(0);
				$table->tinyInteger('user_smoke')->default(0);
				$table->tinyInteger('user_spirt')->default(0);
				$table->tinyInteger('user_help_money')->default(0);
				$table->string('user_speak_lang');
				$table->date('user_make_date')->default('0000-00-00');
				$table->integer('user_make_date_t')->default(0);
				$table->date('user_refresh_date')->default('0000-00-00');
				$table->integer('user_refresh_date_t')->default(0);
				$table->tinyInteger('user_sex_orient')->default(2);
				$table->integer('user_partner_age_min')->default(15);
				$table->integer('user_partner_age_max')->default(15);
				$table->integer('user_partner_height_min')->default(149);
				$table->integer('user_partner_height_max')->default(149);
				$table->integer('user_partner_weight_min')->default(29);
				$table->integer('user_partner_weight_max')->default(29);
				$table->integer('user_partner_country')->default(0);
				$table->integer('user_partner_region')->default(0);
				$table->integer('user_partner_city')->default(0);
				$table->string('user_partner_body');
				$table->string('user_partner_speak_lang');
				$table->string('user_partner_education');
				$table->string('user_partner_smoke');
				$table->string('user_partner_spirt');
				$table->integer('user_session_time')->default(0);
				$table->integer('user_lastvisit')->default(0);
				$table->integer('user_lastvisit_views')->default(0);
				$table->tinyInteger('user_new_message')->default(0);
				$table->text('user_description');
				$table->string('user_target_meet');
				$table->string('user_interests');
				$table->text('user_partner_description');
				$table->integer('user_icq')->default(0);
				$table->string('user_phone');
				$table->string('user_url');
				$table->string('user_ip');
				$table->integer('user_top100')->default(0);
				$table->tinyInteger('user_odobreno')->default(0);
				$table->string('user_submit_code')->nullable();
				$table->tinyInteger('user_confirm_email')->default(1);
				$table->tinyInteger('dont_send_email')->default(0);
				$table->string('remember_token');
			});
		}

		Schema::table('users_news', function (Blueprint $table) {
            $table->primary('user_id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('users_news', function (Blueprint $table) {
			$table->dropPrimary('user_id');
		});
		Schema::dropIfExists('users_news');
	}
};