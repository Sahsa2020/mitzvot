<?php

use /** @noinspection PhpUndefinedClassInspection */
    Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @noinspection PhpUndefinedClassInspection */
        Schema::create('likes', function (Blueprint $table) {
            $table->increments('id');
            /** @noinspection PhpUndefinedMethodInspection */
            $table->integer('on_post')
                ->unsigned()
                ->index();
            /** @noinspection PhpUndefinedMethodInspection */
            $table->integer('liked_by')
                ->unsigned()
                ->index();
            $table->timestamps();
            /* Unique key identifiers... */
            $table->unique(['on_post', 'liked_by']);

            /*
             * Foreign key constraints...
             */
            /** @noinspection PhpUndefinedMethodInspection */
            $table->foreign('on_post')
                ->on('posts')
                ->references('id')
                ->onDelete('cascade');
            /** @noinspection PhpUndefinedMethodInspection */
            $table->foreign('liked_by')
                ->on('users')
                ->references('id')
                ->onDelete('cascade');
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
        Schema::dropIfExists('likes');
    }
}
