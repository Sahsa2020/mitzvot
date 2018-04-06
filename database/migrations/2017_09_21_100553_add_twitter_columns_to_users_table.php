<?php

use /** @noinspection PhpUndefinedClassInspection */
    Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTwitterColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @noinspection PhpUndefinedClassInspection */
        Schema::table('users', function (Blueprint $table) {
            /** @noinspection PhpUndefinedMethodInspection */
            $table->string('oauth_token')
                ->nullable();
            /** @noinspection PhpUndefinedMethodInspection */
            $table->string('oauth_token_secret')
                ->nullable();
            /** @noinspection PhpUndefinedMethodInspection */
            $table->string('user_id')
                ->nullable();
            /** @noinspection PhpUndefinedMethodInspection */
            $table->string('screen_name')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /** @noinspection PhpUndefinedClassInspection */
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(
                'oauth_token',
                'oauth_token_secret',
                'oauth_callback_confirmed'
            );
        });
    }
}
