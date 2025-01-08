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
		if (!Schema::hasTable('users_datas')) {
			Schema::create('users_datas', function (Blueprint $table) {
				$table->integer('id');
				$table->integer('user_id')->default(0);
				$table->string('surname');
				$table->string('name');
				$table->tinyInteger('sex')->default(0);
				$table->integer('icq')->default(0);
				$table->integer('country')->default(0);
				$table->integer('region')->default(0);
				$table->string('city')->default(0);
				$table->tinyInteger('sex_orient')->default(0);
				$table->string('partner_sex');
				$table->string('target_meet');
				$table->date('birth_date')->default('0000-00-00');
				$table->date('make_date')->default('0000-00-00');
				$table->date('refresh_date')->default('0000-00-00');
				$table->string('speak_lang');
				$table->tinyInteger('body')->default(0);
				$table->tinyInteger('height')->default(0);
				$table->tinyInteger('weight')->default(0);
				$table->tinyInteger('hair_color')->default(0);
				$table->tinyInteger('hair_type')->default(0);
				$table->tinyInteger('eyes')->default(0);
				$table->tinyInteger('education')->default(0);
				$table->tinyInteger('smoke')->default(0);
				$table->tinyInteger('spirt')->default(0);
				$table->tinyInteger('sem_polozh')->default(0);
				$table->tinyInteger('children')->default(0);
				$table->tinyInteger('help_money')->default(0);
				$table->string('interest');
				$table->string('url');
				$table->string('phone');
				$table->text('bill');
				$table->integer('partner_age_min')->default(15);
				$table->integer('partner_age_max')->default(15);
				$table->integer('partner_height_min')->default(149);
				$table->integer('partner_height_max')->default(149);
				$table->integer('partner_weight_min')->default(29);
				$table->integer('partner_weight_max')->default(29);
				$table->integer('partner_country')->default(0);
				$table->integer('partner_region')->default(0);
				$table->integer('partner_city')->default(0);
				$table->string('partner_speak_lang');
				$table->string('partner_body');
				$table->string('partner_spirt');
				$table->string('partner_smoke');
				$table->string('partner_education');
				$table->text('partner_description');
				$table->tinyInteger('step3')->default(0);
				$table->string('foto_main')->default(0);
				$table->integer('foto_main_t')->default(0);
				$table->string('foto2')->default(0);
				$table->string('foto3')->default(0);
				$table->string('foto4')->default(0);
				$table->string('foto5')->default(0);
			});
		}

		Schema::table('users_datas', function (Blueprint $table) {
            $table->primary('id');
        });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('users_datas', function (Blueprint $table) {
			$table->dropPrimary('id');
		});
		Schema::dropIfExists('users_datas');
	}
};