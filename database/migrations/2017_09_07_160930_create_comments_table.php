<?php

use /** @noinspection PhpUndefinedClassInspection */
    Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @noinspection PhpUndefinedClassInspection */
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('comment');
            /** @noinspection PhpUndefinedMethodInspection */
            $table->boolean('is_edited')
                ->comment('0 => No, 1 =>Yes')
                ->default(0);
            /** @noinspection PhpUndefinedMethodInspection */
            $table->integer('on_post')
                ->unsigned()
                ->index();
            /** @noinspection PhpUndefinedMethodInspection */
            $table->integer('commented_by')
                ->unsigned()
                ->index();
            $table->timestamps();

            /*
             * Foreign key constraints...
             */
            /** @noinspection PhpUndefinedMethodInspection */
            $table->foreign('on_post')
                ->on('posts')
                ->references('id')
                ->onDelete('cascade');
            /** @noinspection PhpUndefinedMethodInspection */
            $table->foreign('commented_by')
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
        Schema::dropIfExists('comments');
    }
}
