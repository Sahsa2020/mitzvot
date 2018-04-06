<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('age')->default(0);
            $table->string('school', 150)->default('');
            $table->string('company', 150)->default('');
            $table->string('city', 150)->default('');
            $table->string('country', 150)->default('');
            $table->string('address', 150)->default('');
            $table->string('phone', 150)->default('');
            $table->date('birthday')->default('1900-01-01');
            $table->integer('status')->default(0);
            $table->string('activation_token', 150)->default('');
            $table->string('password_token', 150)->default('');
            $table->string('image_url', 150)->default('');
            $table->string('image_origin', 150)->default('');
            $table->integer('del_flg')->default(0);
            $table->integer('goal_daily')->default(0);
            $table->integer('goal_weekly')->default(0);
            $table->integer('goal_monthly')->default(0);
            $table->integer('parent_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('age')->default(0);
            $table->string('school', 150)->default('');
            $table->string('company', 150)->default('');
            $table->string('city', 150)->default('');
            $table->string('country', 150)->default('');
            $table->string('address', 150)->default('');
            $table->string('phone', 150)->default('');
            $table->date('birthday')->default('1900-01-01');
            $table->integer('status')->default(0);
            $table->string('activation_token', 150)->default('');
            $table->string('password_token', 150)->default('');
            $table->string('image_url', 150)->default('');
            $table->string('image_origin', 150)->default('');
            $table->integer('del_flg')->default(0);
            $table->integer('goal_daily')->default(0);
            $table->integer('goal_weekly')->default(0);
            $table->integer('goal_monthly')->default(0);
            $table->integer('parent_id')->default(0);
        });
    }
}
